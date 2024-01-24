<div class="container content">
    <?php
    $currentDateTime = new DateTime();
    ?>
    <?php require_once("./views/Alert.php") ?>
    <div id="cardprovince" class="d-flex flex-wrap gap-1 justify-content-center">
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
</script>