<style>
    /* Styles for printing */
    .print-p {
        padding: 1rem;
    }

    .fs-h {
        font-size: 16pt;
        font-weight: bold;
    }

    .fs-thead {
        font-size: 14pt;
        font-weight: bold;
    }

    .content {
        /* Styles for the content to be printed */
        color: black;
        /* Add more styles as needed */
    }

    /* Hide elements not to be printed */
    .no-print {
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
<div class="content">
    <div>
        <h3 id="title" class="text-center my-4 fs-h"></h3>
    </div>
    <table class="table table-bordered mt-2 table-sm" id="tbshow">
        <thead class="table-light fs-thead">
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
        <button class="btn btn-lg btn-warning w-50 printhide" id="btnback"><i class="bi bi-arrow-left-circle-fill"></i> ກັບຄືນ</button>
        <button class="btn btn-lg btn-primary w-50 printhide" id="btnprint" disabled><i class="bi bi-file-pdf-fill"></i> ປີ້ນໜ້າ</button>
    </div>
</div>
<script>
    const sessiondata = sessionStorage.getItem('printdata');
    if (sessiondata) {
        const printData = JSON.parse(sessiondata);
        const title = printData.title.replace("-","/");
        $("#title").text(title.replace("-","/"));
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
        window.print();
    })
</script>