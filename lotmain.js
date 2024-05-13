window.useJoe = {
    setJo: () => {
        console.log("55 joe");
    },
    joConvertMoney: (data) => {
        const Sales = useJoe.jostr_number(data['Sales']);
        const Award = useJoe.jostr_number(data['Award']);
        const Price = useJoe.jostr_number(data['Price']);
        const Amount = useJoe.jostr_number(data['Amount']);
        return { "Sales": Sales, "Award": Award, "Price": Price, "Amount": Amount };
    },
    jostr_number: (str) => {
        const val = str.replace(/,/g, '');
        const tonumber = parseFloat(val);
        return Number(tonumber);
    },
    joFormatMoney: (number) => {
        const formattedNumber = number.toLocaleString();
        return formattedNumber;
    },
    joTimeNow: () => {
        const currentDate = new Date();
        const day = currentDate.getDate().toString().padStart(2, '0');
        const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
        const year = currentDate.getFullYear();
        const hours = currentDate.getHours().toString().padStart(2, '0');
        const minutes = currentDate.getMinutes().toString().padStart(2, '0');
        const seconds = currentDate.getSeconds().toString().padStart(2, '0');
        const formattedDateTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
        return formattedDateTime;
    },
    joCreateTable: (data, index) => {
        const col = $(`<tr class="text-end">
            <td class='text-center'>${index + 1}</td>
            <td class='text-center'>${data['machineCode']}</td>
            <td>${data['Sales']}</td>
            <td>${data['Award']}</td>
            <td class='text-center col-1'>${data['Percentage']}</td>
            <td class='col-1'>${data['Price']}</td>
            <td>${data['Amount']}</td>
        </tr>`);
        $("#tableData").append(col);
        col.hide();
    },
    joTableToArray: (tableid) => {
        const tableData = [];
        $(`#${tableid} tbody tr`).each(function (rowIndex, row) {
            const rowData = {};
            $(row).find('td').each(function (cellIndex, cell) {
                rowData[(cellIndex)] = $(cell).text().trim();
            });
            tableData.push(rowData);
        });
        return tableData;
    }

}