<?php
require_once "./database/StartPermission.php";
$permission = new UsePermission($_COOKIE['user']);
$unitPermission = $permission->getValue('dataUnit');
// var_dump(ActionCode::Create);
?>
<div class="container-fluid container-lg content">
    <?php require ("./views/Alert.php") ?>
    <form class="d-flex justify-content-between my-2 gap-2" id="fromSearch">
        <div class="me-auto">
            <a class="btn btn-primary" href="?page=formUnit"><i class='bx bxs-plus-circle'></i> ເພີ່ມໜ່ວຍ</a>
        </div>
        <div>
            <select class="form-select" aria-label="Default select example" name="provinceID">
                <?php
                include ("./database/Province_Options.php");
                ?>
            </select>
        </div>
        <div>
            <input type="search" name="search" id="txtsearch" class="form-control" placeholder="ຄົ້ນຫາ">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">ຄົ້ນຫາ</button>
        </div>
    </form>
    <table class="table table-bordered" id="tbshow">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ແຂວງ</th>
                <th scope="col">ໜ່ວຍ</th>
                <th scope="col">ເປີເຊັນ</th>
                <th scope="col">ເຄດິດ</th>
                <th scope="col">ລາງວັນ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
</div>
<script>
    const fromSearch = $('#fromSearch');
    fromSearch.on("submit", (e) => {
        e.preventDefault();
        const formData = fromSearch.serialize();
        $.post(`./api/unitAPI.php?api=search`, formData, (res, err) => {
            $("#tbdata").html("");
            const units = res.data;
            UnitDataTable(units);
        })
    })

    $("#txtsearch").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#tbdata tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    // Onload show unit All
    const show = () => {
        $.get(`./api/unitAPI.php?api=getunits`, (res) => {
            const units = res.data;
            UnitDataTable(units);
        });
    }
    show();



    const UnitDataTable = (units) => {
        units.forEach((unit, index) => {
            const tr = $("<tr></tr>");
            const rowmoneyState = $("<td class='text-center'></td>");
            const checkState = $(`<input type="checkbox" class="form-check-input" name="moneyState" id="moneyState" ${unit['withdrawn'] == 1 ? "checked" : ""}>`);
            const buttondelete = $(`<button class="btn btn-danger btn-sm"><i class='bx bxs-trash' ></i></button>`);
            const action = $(`
                    <td class="col-2 text-center">
                        <a href="?page=editunit&id=${unit['unitID']}" class="btn btn-success btn-sm"><i class='bx bxs-edit' ></i></a>                  
                    </td>`);
            action.append(buttondelete);
            tr.html(`
                    <th scope="row" class="text-center">${index + 1}</th>
                    <td>${unit['pname']}</td>
                    <td>${unit['unitName']}</td>
                    <td class="text-center">${unit['Percentage']}%</td>
                    <td class="text-center">${unit['credit']}</td>
                `);
            rowmoneyState.append(checkState);
            tr.append(rowmoneyState);
            tr.append(action);
            // Change state
            const isChecked = checkState.prop('checked');
            checkState.on('change', function () {
                UpdateState(unit['unitID']);
            });
            // Delete Unit
            buttondelete.click(() => {
                DeleteUnit(unit['unitID']);
            });
            $('#tbdata').append(tr);
        });
    }


    const UpdateState = (unitID) => {
        Swal.fire({
            title: "ກຳນົດການຮັບເງິນ",
            text: "ທ່ານຕ້ອງການປ່ຽນແປງຂໍ້ມູນຫຼືບໍ່!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ປ່ຽນແປງ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            const newState = $(this).prop('checked');
            if (result.isConfirmed) {
                const upres = $.post(`./api/unitAPI.php?api=updateState&id=${unitID}`, {
                    "moneyState": newState ? 1 : 0
                }, (result) => {
                    console.log(result);
                });
            } else {
                checkState.prop('checked', !newState);
            }
        });
    }

    const DeleteUnit = (unitID) => {
        Swal.fire({
            title: "ລົບຂໍ້ມູນ",
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນຫຼືບໍ່!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ລົບໜ່ວຍ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            const newState = $(this).prop('checked');
            if (result.isConfirmed) {
                const upres = $.get(`./api/unitAPI.php?api=delete&id=${unitID}`, (result) => {
                    console.log(result);
                    if (result.state) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>