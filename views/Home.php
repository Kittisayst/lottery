<div class="container content">
    <?php
    $currentDateTime = new DateTime();
    ?>
    <?php require_once ("./views/Alert.php") ?>
    <div id="cardprovince" class="d-flex flex-wrap gap-1 justify-content-center">
    </div>
    <div class="d-flex justify-content-center">
        <div class="col-6 mt-5 mb-3 bg-light rounded-2 p-1 text-center">
            <canvas id="myChart"></canvas>
        </div>
    </div>

</div>
<script>
    const show = async () => {
        const res = await fetch("./api/ProvinceAPI.php?api=getprovinces")
        const result = await res.json();
        const provinces = result.data;
        provinces.forEach((province) => {
            const card = $(`<div class='col-12 col-sm-5 col-md-3 col-lg-2 bg-warning-subtle rounded-3 btn'></div>`);
            card.html(`
                <a href="?page=unit&provinceID=${province['pid']}" class="nav-link  d-flex flex-column justify-content-center align-items-center" style='height: 80px;'>
                    <span>${province['pname']}</span>
                    <span style="font-size: 12px;" class="mt-2 text-secondary" id="count${province['pid']}">ຈຳນວນ: 0 ໜ່ວຍ</span>
                </a>
                `);
            $("#cardprovince").append(card);
            countUnit(province);
        });
    }

    show();

    const countUnit = async (province) => {
        const res = await fetch(`./api/unitAPI.php?api=getUnitsbyProvinID&provinceID=${province['pid']}`);
        const result = await res.json();
        const count = result.data.length;
        $(`#count${province['pid']}`).text(`ຈຳນວນ: ${count} ໜ່ວຍ`);
    }

    const createChart = async () => {
        const api = await fetch("./api/ChartAPI.php?api=sales");
        const res = await api.json();
        const lotapi = await fetch("./api/ChartAPI.php?api=lottery");
        const reslot = await lotapi.json();


        const salesData = [];
        res.data.forEach(data => {
            salesData.push(Number(data));
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: reslot.data.reverse(),
                datasets: [{
                    label: 'ຍອດຂາຍ',
                    data: salesData.reverse(),
                    fill: true,
                    borderColor: 'rgb(21, 115, 71)',
                    backgroundColor: 'rgb(27, 153, 94, 0.6)',
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14,
                                family: 'Noto Sans Lao'
                            }
                        }
                    },
                    label: {
                        font: {
                            family: "Noto Sans Lao"
                        }
                    },
                    tooltip: {
                        titleFont: {
                            family: "Noto Sans Lao"
                        },
                        bodyFont: {
                            family: "Noto Sans Lao"
                        },
                        footerFont: {
                            family: "Noto Sans Lao"
                        }
                    }
                }
            }
        });
    }

    createChart();
</script>