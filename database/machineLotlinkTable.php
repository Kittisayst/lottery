<?php

if (isset($_GET['pid']) && $_GET['unitID']) {
    require_once ("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_machinelotlink
    INNER JOIN tb_unit ON tb_machinelotlink.UnitID=tb_unit.unitID
    WHERE tb_unit.provinceID=? AND tb_unit.unitID=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$_GET['pid'], $_GET['unitID']]);
    $result = $stmt->fetchAll();
    if ($result) {
        $index = 1;
        foreach ($result as $item) {
            ?>
            <tr class="text-center">
                <td>
                    <?= $index++ ?>
                </td>
                <td>
                    <?= $item['unitName'] ?>
                </td>
                <td>
                    <?= $item['machineCode'] ?>
                </td>
                <td class="col-2">
                    <button class="btn btn-success btn-sm" onclick="edit(<?= $item['machineID'] ?>)"><i
                            class='bx bxs-edit-alt'></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deleteMachine(<?= $item['machineID'] ?>)"><i
                            class='bx bxs-trash'></i></button>
                </td>
            </tr>
            <?php
        }
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>............ ຄົ້ນຫາຂໍ້ມູນໜ່ວຍ ............</td></tr>";
}
?>
<script>
    const edit = async (id) => {
        const res = await fetch(`./api/ProvinceAPI.php?api=getprovinces`);
        const read = await res.json();
        const province = read.data;
        const fetmachine = await fetch(`./api/sellCodeLotlink.php?api=getbyid&id=${id}`);
        const jsonMachine = await fetmachine.json();
        const machineData = jsonMachine.data[0];

        Swal.fire({
            title: "ແກ້ໄຂລະຫັດຜູ້ຂາຍ",
            html: `
                <form class="px-2" id="frmMachine">
                    <div class="mb-3">
                        <label for="prov" class="form-label w-100 text-start">ແຂວງ</label>
                        <select class="form-select" name="pid" id="provi">
                        <option value="0" disibled>......ເລືອກແຂວງ......</option>
                        ${ProvinceOption(province, machineData['provinceID'])}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label w-100 text-start">ໜ່ວຍ</label>
                        <select class="form-select" name="unid" id="unit">
                            
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="txtcode" class="form-label w-100 text-start">ລະຫັດຜູ້ຂາຍ</label>
                        <input type="text" class="form-control" name="sellcode" placeholder="ລະຫັດຜູ້ຂາຍ" value="${machineData['machineCode']}" required>
                    </div>
                    <div class="mb-3 mt-3">
                        <button class="btn btn-primary w-100" type="submit"><i class='bx bxs-save' ></i> ແກ້ໄຂລະຫັດຜູ້ຂາຍ</button>
                    </div>
                </form>`,
            showConfirmButton: false,
            focusCancel: false,
        });

        $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${machineData['provinceID']}`, (res) => {
            $("#unit").html("");
            const units = res.data;
            $("#unit").html(UnitOption(units));
        });

        $("#provi").on("change", async (e) => {
            const provinceID = e.target.value;
            $.get(`./api/unitAPI.php?api=unitbyprovinid&pid=${provinceID}`, (res) => {
                $("#unit").html("");
                const units = res.data;
                $("#unit").html(UnitOption(units));
            });
        });

        const frmMachine = $("#frmMachine");
        frmMachine.submit((e) => {
            e.preventDefault();
            const frmData = frmMachine.serialize();
            $.post(`./api/sellCodeLotlink.php?api=update&id=${id}`, frmData, (res) => {
                if (res.state) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                }
            });
        });
    }

    const deleteMachine = (id) => {
        Swal.fire({
            title: 'ລົບຂໍ້ມູນ?',
            text: 'ທ່ານຕ້ອງການລົບຂໍ້ມູນນີ້ ຫຼື ບໍ່!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ລົບຂໍ້ມູນ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`./api/sellCodeLotlink.php?api=delete&id=${id}`, (res) => {
                    if (res.state) {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => location.reload());
                    }
                });
            }
        });
    }

    const ProvinceOption = (provinces, id) => {
        let str = "";
        console.log(id);
        provinces.forEach(province => {
            if (province['pid'] == id) {
                str += `<option value="${province['pid']}" selected>${province['pname']}</option>`;
            } else {
                str += `<option value="${province['pid']}">${province['pname']}</option>`;
            }
        });
        return str;
    }

    const UnitOption = (unitdata) => {
        let str = "";
        unitdata.forEach(unit => {
            str += `<option value="${unit['unitID']}">${unit['unitName']}</option>`;
        });
        return str;
    }
</script>