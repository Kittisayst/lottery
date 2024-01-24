function formatDate(inputDate) {
    var dateParts = inputDate.split("-");
    var formattedDate = dateParts[2] + "/" + dateParts[1] + "/" + dateParts[0];
    return formattedDate;
}