<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="me-auto">
        <button class="btn btn-primary" id="btnAdd"><i class='bx bxs-plus-circle'></i> ເພີ່ມລະຫັດຜູ້ຂາຍ</button>
    </div>
    <form id="frmSearch">
        <div class="mb-3 d-flex justify-content-end gap-2">
            <div class="d-flex align-items-center gap-2">
                <label for="cbProvince" class="form-label">ແຂວງ</label>
                <select class="form-select" name="provinceID" id="cbProvince">
                    <?php
                    include_once ("./database/Province_Options.php");
                    ?>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="cbUnit" class="form-label">ໜ່ວຍ</label>
                <select class="form-select" name="unitid" id="cbUnit">
                    <?php
                    include_once ("./database/unit_Option.php");
                    ?>
                </select>
            </div>
            <div>
                <?php
                $searhdata = isset($_GET['search']) ? $_GET['search'] : "";
                ?>
                <input type="search" name="search" id="txtSearch" class="form-control" placeholder="ຊື່ໜ່ວຍ"
                    value="<?= $searhdata ?>">
            </div>
            <div>
                <button type="submit" class="btn btn-info"><i class='bx bx-search'></i> ສະແດງ</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ລະຫັດຜູ້ຂາຍ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tableData">
            <?php
            require_once ("./database/machineTable.php");
            ?>
        </tbody>
    </table>
</div>

<script>
    const formsearch = $('#frmSearch');

    formsearch.on("submit", (e) => {
        e.preventDefault();
        const formData = formsearch.serializeArray();
        console.log(formData);
        location.href = `?page=lotmachine&pid=${formData[0].value}&unitID=${formData[1].value}&search=${formData[2].value}`;
    });

    $("#cbProvince").on("change", (e) => {
        const provinceID = e.target.value;
        $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res, err) => {
            $("#cbUnit").html("");
            const units = res.data;
            const optionall = $(`<option value="0">---ໜ່ວຍທັງໝົດ---</option>`);
            $("#cbUnit").append(optionall);
            units.forEach(unit => {
                const option = $(`<option value="${unit['unitID']}">${unit['unitName']}</option>`);
                $("#cbUnit").append(option);
            });
        });
    });

    $("#txtSearch").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#tableData tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });


    $("#btnAdd").click(async (e) => {
        const res = await fetch(`./api/ProvinceAPI.php?api=getprovinces`);
        const read = await res.json();
        const province = read.data;
        Swal.fire({
            title: "ເພີ່ມລະຫັດຜູ້ຂາຍ",
            html: `
                <form class="px-2" id="frmMachine">
                    <div class="mb-3">
                        <label for="prov" class="form-label w-100 text-start">ແຂວງ</label>
                        <select class="form-select" name="pid" id="prov">
                        <option value="0" disibled>......ເລືອກແຂວງ......</option>
                        ${createProvince(province)}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label w-100 text-start">ໜ່ວຍ</label>
                        <select class="form-select" name="unid" id="unit">
                            
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="txtcode" class="form-label w-100 text-start">ລະຫັດຜູ້ຂາຍ</label>
                        <input type="text" class="form-control" name="sellcode" placeholder="ລະຫັດຜູ້ຂາຍ" required>
                    </div>
                    <div class="mb-4">
                        <div class="alert alert-danger" role="alert" id="ms">
                            
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <button class="btn btn-primary w-100" type="submit"><i class='bx bxs-save' ></i> ບັນທຶກລະຫັດຜູ້ຂາຍ</button>
                    </div>
                </form>`,
            showConfirmButton: false,
            focusCancel: false,
        });

        $("#ms").hide();

        $("#prov").on("change", async (e) => {
            const provinceID = e.target.value;
            $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res) => {
                $("#unit").html("");
                const units = res.data;
                $("#unit").html(createUnit(units));
            });

        });

        const frmMachine = $("#frmMachine");
        frmMachine.submit((e) => {
            e.preventDefault();
            const frmData = frmMachine.serialize();
            $.post(`./api/sellCodeAPI.php?api=create`, frmData, (res) => {
                if (res.state) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(()=>location.reload());
                } else {
                    $("#ms").show();
                    $("#ms").text(res.message);
                    setTimeout(() => {
                        $("#ms").hide();
                    }, 2500);
                }
            });
        });
    });

    const createProvince = (provinces) => {
        let str = "";
        provinces.forEach(province => {
            str += `<option value="${province['pid']}">${province['pname']}</option>`;
        });
        return str;
    }

    const createUnit = (unitdata) => {
        let str = "";
        unitdata.forEach(unit => {
            str += `<option value="${unit['unitID']}">${unit['unitName']}</option>`;
        });
        return str;
    }
</script>