<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="mb-3">
        <a href="?page=createuser" class="btn btn-primary">ເພີ່ມຜູ້ໃຊ້ງານ</a>
    </div>
    <table class="table table-bordered" id="tbshow">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ຊື່ຜູ້ໃຊ້ງານ</th>
                <th scope="col">ຜູ້ໃຊ້ງານ</th>
                <th scope="col">ສິດທິ</th>
                <th scope="col">ໃຊ້ງານລ່າສຸດ</th>
                <th scope="col" class="col-2">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            require_once "./database/TableUser.php";
            ?>
        </tbody>
    </table>
</div>

<script>

    const edit = (id) => {
        location.href = `?page=edituser&id=${id}`;
    }

    const handelPermission = (id) => {
        location.href = `?page=permission&id=${id}`;
    }

    const addPermission = async (id) => {
        console.log(id);
        const optionPermiss = await loadPermissions();
        Swal.fire({
            title: "ກຳນົດສິດທິ",
            html: `<form class="px-2" id="frmPermission">
                    <input type="hidden" name="userID" value="${id}">
                    <div class="mb-3">
                        <label for="cbpermission" class="form-label text-start w-100">ສິດທິ</label>
                        <select class="form-select" id="cbpermission" name="permissionID">
                        ${optionPermiss}
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-success w-100" type="submit">ກຳນົດສິດທິ</button>
                    </div>
                    </form>
                    `,
            showConfirmButton: false,
            showCloseButton: true,
            focusCancel: false
        });

        const frmPermission = $("#frmPermission");
        frmPermission.on("submit", (e) => {
            e.preventDefault();
            $data = frmPermission.serialize();
            $.post(`./api/DBPermission.php?api=create`, $data, (res) => {
                if (res.state) {
                    var selectedText = $('#cbpermission option:selected').text();
                    $(`#u${id}`).html(selectedText);
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        })
    }

    const loadPermissions = async () => {
        const res = await fetch(`./api/PermissionAPI.php?api=all`);
        const data = await res.json();
        const permissions = data.data;
        let str = "";
        permissions.forEach(item => {
            str += `<option value="${item.permissionID}">${item.name}</option>`;
        });
        return str;
    }


    const deleteuser = (id) => {
        Swal.fire({
            title: "ລົບຂໍ້ມູນ",
            text: "ທ່ານຕ້ອງການລົບຂໍ້ມູນຫຼືບໍ່!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ລົບຜູ້ໃຊ້ງານ",
            cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
            const newState = $(this).prop('checked');
            if (result.isConfirmed) {
                const upres = $.get(`./api/userAPI.php?api=delete&id=${id}`, (result) => {
                    if (result.state) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>