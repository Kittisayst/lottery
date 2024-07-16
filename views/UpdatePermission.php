<div class="container content">
    <?php
    require_once ("./views/Alert.php");
    ?>
    <form id="frmPermission" class="bg-body-secondary p-3">
        <div class="d-flex justify-content-center mb-3">
            <div class="col-6 ">
                <label for="txtpermission" class="form-label">ຊື່ສິດທິ</label>
                <input type="text" class="form-control" id="txtpermission" required>
            </div>
        </div>
        <div>
            <hr>
        </div>
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5" id="viewPermission">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">ລຳດັບ</th>
                        <th scope="col" class="text-center">ລາຍການ</th>
                        <th scope="col" class="text-center">ສິດທິການໃຊ້ງານ</th>
                    </tr>
                </thead>
                <tbody id="tabledata">

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center gap-3">
            <button type="submit" class="btn btn-lg btn-secondary col-3">
                <i class="bi bi-floppy-fill"></i>
                ບັນທຶກ
            </button>
            <a href="?page=permission" class="btn btn-warning btn-lg col-3">ຍົກເລີກ</a>
        </div>
    </form>
</div>
<script>

    const readPermissions = async () => {
        const res = await fetch(`./api/PermissionAPI.php?api=byid&id=<?= $_GET['id'] ?>`);
        const data = await res.json();
        const permissions = JSON.parse(data.data['permission'] == "" ? "{}" : data.data['permission']);
        $("#txtpermission").val(data.data['name'] );
        return permissions;
    }

    const showPermissions = async () => {
        const res = await fetch("./database/permission.json");
        const datas = await res.json();
        const reads = await readPermissions();
        const view = $("#tabledata");
        datas.permissions.forEach((permission, index) => {
            const value = {
                name: permission.name,
                sname: permission.sname,
                key: permission.key,
                none: permission.none,
                read: permission.read,
                create: permission.create,
                edit: permission.edit,
                delete: permission.delete
            };

            const checkRead = value.read ? "" : "disabled";
            const checkCreate = value.create ? "" : "disabled";
            const checkUpdate = value.edit ? "" : "disabled";
            const checkDelete = value.delete ? "" : "disabled";

            //ກວດສອບ
            let isNone = "";
            let isRead = "";
            let isCreate = "";
            let isUpdate = "";
            let isDelete = "";
            const isObject = Object.keys(reads).length === 0;
            if (!isObject) {
                const getValue = reads.find(entry => entry.hasOwnProperty(value.key));
                if (getValue) {
                    const valuePermissions = getValue[value.key];
                    if (valuePermissions) {
                        isNone = valuePermissions.includes(1) ? "checked" : "";
                        isRead = valuePermissions.includes(2) ? "checked" : "";
                        isCreate = valuePermissions.includes(3) ? "checked" : "";
                        isUpdate = valuePermissions.includes(4) ? "checked" : "";
                        isDelete = valuePermissions.includes(5) ? "checked" : "";
                    }
                }
            }
            const row = $(`
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${value.name}</td>
            </tr>`
            );

            const rowPermission = $(
                `<td class="d-flex gap-5">

                </td>`);

            const rowNone = $(`                    
            <div>
                <input class="form-check-input" type="checkbox" value="1" id="ck1${index}" name="${value.key}" ${isNone}>
                    <label class="form-check-label ms-2" for="ck1${index}">
                        ນຳໃຊ້
                    </label>
            </div>`);
            const rowRead = $(`
            <div>
                <input class="form-check-input" type="checkbox" value="2" id="ck2${index}" name="${value.key}" ${checkRead} ${isRead}>
                    <label class="form-check-label ms-2" for="ck2${index}">
                        ສະແເດງຂໍ້ມູນ
                    </label>
            </div>`);
            const rowCreate = $(`
            <div>
                <input class="form-check-input" type="checkbox" value="3" id="ck3${index}" name="${value.key}" ${checkCreate} ${isCreate}>
                    <label class="form-check-label ms-2" for="ck3${index}">
                        ເພີ່ມຂໍ້ມູນ
                    </label>
            </div>`);
            const rowUpdate = $(`
            <div>
                <input class="form-check-input" type="checkbox" value="4" id="ck4${index}" name="${value.key}" ${checkUpdate} ${isUpdate}>
                <label class="form-check-label ms-2" for="ck4${index}">
                    ແກ້ໄຂຂໍ້ມູນ
                </label>
            </div>`);
            const rowDelete = $(`
            <div>
                <input class="form-check-input" type="checkbox" value="5" id="ck5${index}" name="${value.key}" ${checkDelete} ${isDelete}>
                <label class="form-check-label ms-2" for="ck5${index}">
                    ລົບຂໍ້ມູນ
                </label>
            </div>`);


            row.append(rowPermission);
            view.append(row);
            if (value.none) {
                rowPermission.append(rowNone);
            }
            if (value.read) {
                rowPermission.append(rowRead);
            }
            if (value.create) {
                rowPermission.append(rowCreate);
            }
            if (value.edit) {
                rowPermission.append(rowUpdate);
            }
            if (value.delete) {
                rowPermission.append(rowDelete);
            }
        });

    }
    showPermissions();

    const frmPermission = $("#frmPermission");
    frmPermission.on("submit", (e) => {
        e.preventDefault();
        let result = frmPermission.serializeArray().reduce((acc, { name, value }) => {
            let existing = acc.find(item => Object.keys(item)[0] === name);
            if (existing) {
                existing[name].push(Number(value));
            } else {
                let newItem = {};
                newItem[name] = [Number(value)];
                acc.push(newItem);
            }
            return acc;
        }, []);

        console.log(result);
        const name = $("#txtpermission").val();
        $.post("./api/PermissionAPI.php?api=update&id=<?= $_GET['id'] ?>",
            { "name": name, "permission": JSON.stringify(result) },
            (res) => {
                if (res.state) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).finally(() => { location.href = "?page=permission" });
                }
            });
    });
</script>