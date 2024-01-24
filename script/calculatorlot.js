const calculatorlot = (sales, percentage, award) => {
    const fsales = Number(sales);
    const fpercentage = Number(percentage);
    const faward = Number(award);
    const calpercent = (fsales * fpercentage) / 100;
    const caltotal = fsales - calpercent;
    const amout = caltotal - faward;
    return { percentageMoney: calpercent, totalMoney: caltotal, amoutMoney: amout };
};

function jDateformat(inputDate) {
    var dateParts = inputDate.split("-");
    var formattedDate = dateParts[2] + "/" + dateParts[1] + "/" + dateParts[0];
    return formattedDate;
}

const jFormatMoney = (money) => {
    const formattedValue = new Intl.NumberFormat('en-US').format(money);
    return formattedValue;
}

const jdateNow = () => {
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0');
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    const year = currentDate.getFullYear();
    const formattedDateTime = `${day}/${month}/${year}`;
    return formattedDateTime;
}

const jdateTimeNow = () => {
    const currentDate = new Date();
    const day = currentDate.getDate().toString().padStart(2, '0');
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    const year = currentDate.getFullYear();
    const hours = currentDate.getHours().toString().padStart(2, '0');
    const minutes = currentDate.getMinutes().toString().padStart(2, '0');
    const seconds = currentDate.getSeconds().toString().padStart(2, '0');
    const formattedDateTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    return formattedDateTime;
}