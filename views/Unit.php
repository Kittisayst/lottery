<div class="container content">
    <h1 class="text-center py-2" id="title">ໜ່ວຍແຂວງ</h1>
    <div id="content" class="d-flex gap-1 flex-wrap">

    </div>
</div>
<script>
    // Get the query string from the current URL
    const queryString = window.location.search;
    // Create a URLSearchParams object using the query string
    const urlParams = new URLSearchParams(queryString);
    const provinceID = urlParams.get('provinceID');
    //show Title
    $.get(`./api/ProvinceAPI.php?api=getprovincesbyID&id=${provinceID}`, (res) => {
        $("#title").text("ໜ່ວຍປະຈຳແຂວງ: "+res.data[0]['pname']);
    });
    const show = async () => {
        const res = await fetch(`./api/unitAPI.php?api=getUnitsbyProvinID&provinceID=${provinceID}`);
        const result = await res.json();
        if (result.state) {
            const units = result.data;
            units.forEach(unit => {
                const card = $("<div class='col-12 col-sm-5 col-md-3 col-lg-2 bg-warning-subtle rounded-3 btn'></div>");
                card.html(`
                <a href="#" class="nav-link  d-flex flex-column justify-content-center align-items-center" style='height: 80px;'>
                ${unit['unitName']}
                </a>`);
                $("#content").append(card);
            });
        } else {
            console.log("Failed to");
        }
    }
    show();
</script>