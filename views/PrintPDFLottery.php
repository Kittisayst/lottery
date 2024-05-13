<style>
    #navid {
        display: none;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 2.5mm;
            /* Adjust margins as needed */
        }

        * {
            font-family: "Phetsarath OT", sans-serif;
            font-size: 11pt;
        }

        .print-p {
            padding: 0;
        }

        body {
            margin: 0;
            padding: 5mm;
            /* Add padding to prevent content from getting too close to the edges */
        }

        .fs-h {
            font-size: 14pt;
            font-weight: bold;
        }

        .fs-thead {
            font-size: 2pt;
            font-weight: bold;
        }

        .fs-body {
            font-size: 5pt;
        }

        #navid {
            display: none;
        }

        footer {
            display: none;
        }

        .printhide {
            display: none;
        }
    }
</style>
<div class="content" id="printid">
    <div>
        <h3 id="title" class="text-center my-4 fs-h"></h3>
    </div>
    <table id="tbshow" class="w-100 table-bordered">
        <thead class="">
            <tr class="text-center">
                <th scope="col">ລຳດັບ</th>
                <th scope="col">ລະຫັດຜູ້ຂາຍ</th>
                <th scope="col">ເລກບິນ</th>
                <th scope="col">ຖືກເລກ 1 ໂຕ</th>
                <th scope="col">ຖືກເລກ 2 ໂຕ</th>
                <th scope="col">ຖືກເລກ 3 ໂຕ</th>
                <th scope="col">ຖືກເລກ 4 ໂຕ</th>
                <th scope="col">ຖືກເລກ 5 ໂຕ</th>
                <th scope="col">ຖືກເລກ 6 ໂຕ</th>
                <th scope="col">ລວມ</th>
                <th scope="col">ໝາຍເຫດ</th>
            </tr>
        </thead>
        <tbody id="tableData" class="fs-body">

        </tbody>
    </table>
    <div class="d-flex gap-3">
        <a class="btn btn-lg btn-warning w-50 printhide" href="?page=scanpdflottery"><i
                class="bi bi-arrow-left-circle-fill"></i> ກັບຄືນ</a>
        <button class="btn btn-lg btn-primary w-50 printhide" id="btnprint" disabled><i class="bi bi-file-pdf-fill"></i>
            ປີ້ນໜ້າ</button>
    </div>
</div>
<a href="./printfpdp.php">print</a>
<script type="module">
    const sessiondata = sessionStorage.getItem('printdata');
    if (sessiondata) {
        const printData = JSON.parse(sessiondata);
        const title = printData.title.replace("-", "/");
        $("#title").text(title.replace("-", "/"));
        const datas = printData.print;
        datas.shift();
        datas.forEach((lot, index) => {
            if (datas.length > index + 1) {
                const col = $(`<tr class="text-end"></tr>`);
                col.html(`
                    <td class="text-center">${index + 1}</td>
                    <td class="text-center">${lot[1]}</td>
                    <td class="text-center">${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td>${lot[4]}</td>
                    <td>${lot[5]}</td>
                    <td>${lot[6]}</td>
                    <td>${lot[7]}</td>
                    <td>${lot[8]}</td>
                    <td>${lot[9]}</td>
                    <td></td>`);
                $("#tableData").append(col);
            } else {
                const col = $(`<tr class="text-end"></tr>`);
                col.html(`<td colspan="3" class="text-center">ລ່ວມທັງໝົດ</td>
                    <td>${lot[1]}</td>
                    <td>${lot[2]}</td>
                    <td>${lot[3]}</td>
                    <td>${lot[4]}</td>
                    <td>${lot[5]}</td>
                    <td>${lot[6]}</td>
                    <td>${lot[7]}</td>
                    <td></td>`);
                //ສະແດງຂໍ້ມູນຕາຕະລາງ
                $("#tableData").append(col);
                $("#btnprint").removeAttr('disabled');
            }
        });
    } else {
        location.href = "?page=printpdflottery";
    }

    $("#btnback").click(() => {
        history.back();
    });

    $("#btnprint").click(() => {
        createPDF();
    })


    async function createPDF() {
        const { PDFDocument, rgb } = PDFLib;
        const pdfDoc = await PDFDocument.create();

        const fontkit = window.fontkit;
        try {
            const fonturl = './boonhome-400.otf';
            const fontBytes = await fetch(fonturl).then((res) => res.arrayBuffer());
            pdfDoc.registerFontkit(fontkit)
            const customFont = await pdfDoc.embedFont(fontBytes);

            const A4Landscap = [841.89, 595.28];
            let page = pdfDoc.addPage(A4Landscap);
            const fontSize = 14;
            const topMargin = 10;
            const leftMargin = 5;
            const linespace = 5;
            const { width, height } = page.getSize();
            const text = "ຖືກລາງວັນ ແຂວງ ທັງໝົດ ໜ່ວຍ ທັງໝົດ ງວດທີ 24043 ວັນທີ: 12/04/2024 ເລກທີ່ອອກ 439432";
            const textsize = getTextSize(customFont, text, fontSize);
            const startY = height - textsize.height;
            const endX = width - textsize.width;

            page.drawText(text, {
                x: endX / 2,
                y: startY - topMargin,
                size: fontSize,
                font: customFont,
            });

            const arrheader = ["ລຳດັບ", "ລະຫັດຜູ້ຂາຍ", "ເລກບິນ", "ຖືກເລກ 1 ໂຕ", "ຖືກເລກ 2 ໂຕ", "ຖືກເລກ 3 ໂຕ", "ຖືກເລກ 4 ໂຕ", "ຖືກເລກ 5 ໂຕ", "ຖືກເລກ 6 ໂຕ", "ລວມ", "ໝາຍເຫດ"];
            let rowspace = -50;
            const arrX = [5, 35, 99.33333333333334, 266.5, 336.25, 407.1666666666667, 478.25, 549.25, 620.4166666666666, 691.6666666666666, 776.75];
            const arrTextwidth = [22, 46.33333333333334, 26.16666666666667, 45.75, 46.91666666666667, 47.08333333333333, 47, 47.16666666666667, 47.25, 17.083333333333336, 35.666666666666664];
            const arrBorderW = [0, 10, 133, 16, 16, 16, 16, 16, 16, 60, 16];
            arrheader.forEach((text, index) => {
                const Row = createRow(page, rgb, arrX[index], -50, text, arrTextwidth[index], 10, customFont, arrBorderW[index], "center");
            });

            const arrData = [
                ["1", "2111001006", "2404331828334435323629020", "0", "600,000", "0", "0", "0", "0", "600,000", ""],
                ["2", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["3", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["4", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["5", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["6", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["7", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["8", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["9", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["10", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["11", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["12", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["13", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["14", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["15", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["16", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["17", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["18", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["19", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["20", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["21", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["22", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["23", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["24", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["25", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["26", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["27", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["28", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["29", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""],
                ["30", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""]
            ];

            // ,
            //     ["1", "2111001011", "2404331828334435323629020", "0", "300,000", "0", "0", "0", "0", "300,000", ""]
            
            let rowheight = -50 - 21.166666666666664;

            arrData.forEach((lots, index) => {
                if (rowheight <= -580) {
                    page = pdfDoc.addPage(A4Landscap);
                    rowheight = -25;
                }
                createRow(page, rgb, arrX[0], rowheight, lots[0], arrTextwidth[0], 10, customFont, arrBorderW[0], "center");
                createRow(page, rgb, arrX[1], rowheight, lots[1], arrTextwidth[1], 10, customFont, arrBorderW[1], "center");
                createRow(page, rgb, arrX[2], rowheight, lots[2], arrTextwidth[2], 10, customFont, arrBorderW[2], "center");
                createRow(page, rgb, arrX[3], rowheight, lots[3], arrTextwidth[3], 10, customFont, arrBorderW[3], "end");
                createRow(page, rgb, arrX[4], rowheight, lots[4], arrTextwidth[4], 10, customFont, arrBorderW[4], "end");
                createRow(page, rgb, arrX[5], rowheight, lots[5], arrTextwidth[5], 10, customFont, arrBorderW[5], "end");
                createRow(page, rgb, arrX[6], rowheight, lots[6], arrTextwidth[6], 10, customFont, arrBorderW[6], "end");
                createRow(page, rgb, arrX[7], rowheight, lots[7], arrTextwidth[7], 10, customFont, arrBorderW[7], "end");
                createRow(page, rgb, arrX[8], rowheight, lots[8], arrTextwidth[8], 10, customFont, arrBorderW[8], "end");
                createRow(page, rgb, arrX[9], rowheight, lots[9], arrTextwidth[9], 10, customFont, arrBorderW[9], "end");
                createRow(page, rgb, arrX[10], rowheight, lots[10], arrTextwidth[10], 10, customFont, arrBorderW[10], "");
                rowheight += - 21.166666666666664;
            });

            // Serialize the PDF document to bytes
            const pdfBytes = await pdfDoc.save();

            // Download the PDF (assuming you have this logic)
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement('a');
            link.href = url;
            link.download = 'custom_pdf.pdf';
            link.click();

            URL.revokeObjectURL(url);
        } catch (error) {
            console.error("Error creating PDF:", error);
        }
    }

    const getTextSize = (customFont, text, size) => {
        const textWidth = customFont.widthOfTextAtSize(text, size)
        const textHeight = customFont.heightAtSize(size) - size + 2
        return { width: textWidth, height: textHeight, text: text, fontSize: size, font: customFont }
    }

    const createRow = (page, rgb, x = 0, y = 0, text, textWidth, fontsize, customFont, lineW = 0, textAlign = "start") => {
        const { width, height } = page.getSize();
        const textData = getTextSize(customFont, text, fontsize);
        const pading = 8;
        const lineY = y + height - textData.height;
        const lineWidth = textWidth + pading + lineW;
        const lineHeight = textData.height + pading * 2;
        let positionX = x;
        switch (textAlign) {
            case 'start':
                positionX = x + pading / 2;
                break;
            case 'center':
                positionX = x + lineWidth / 2 - textData.width / 2;
                break;
            case 'end':
                positionX = x + (lineWidth / 2 - textData.width / 2) * 2 - pading / 2;
                break;
            default:
                positionX = x + pading / 2;
                break;
        }
        const positionY = y + height - textData.height + pading / 1.1;
        page.drawText(text, {
            x: positionX,
            y: positionY,
            size: fontsize,
            font: customFont,
        });

        page.drawRectangle({
            x: x,
            y: lineY,
            width: lineWidth,
            height: lineHeight,
            borderColor: rgb(0, 0, 0),
            borderWidth: 1,
        });

        return { width: lineWidth, height: lineHeight, y: y + height - textData.height };
    }


</script>