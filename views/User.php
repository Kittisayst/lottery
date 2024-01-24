<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <div class="mb-3">
        <a href="?page=createuser" class="btn btn-primary">ເພີ່ມຜູ້ໃຊ້ງານ</a>
    </div>
    <table class="table table-bordered" id="tbshow">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ຊື່ຜູ້ໃຊ້ງານ</th>
                <th scope="col">ຜູ້ໃຊ້ງານ</th>
                <th scope="col">ໃຊ້ງານລ່າສຸດ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">

        </tbody>
    </table>
</div>

<script>
    const show = () => {
        $.get(`./api/userAPI.php?api=getusers`, (res) => {
            const users = res.data;
            users.forEach((user, index) => {
                const tr = $("<tr></tr>");
                const action = $(`
                    <td class="col-2 text-center">
                        <a href="?page=edituser&id=${user['userID']}" class="btn btn-success btn-sm"><i class='bx bxs-edit' ></i></a>
                    </td>`);
                const buttonDelete = $(`<button href="#" class="btn btn-danger btn-sm"><i class='bx bxs-trash' ></i></button>`);
                buttonDelete.click(() => {
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
                            const upres = $.get(`./api/userAPI.php?api=delete&id=${user['userID']}`, (result) => {
                                if (result.state) {
                                    location.reload();
                                }
                            });
                        }
                    });
                });
                action.append(buttonDelete);
                tr.html(`
                    <th scope="row" class="text-center">${index+1}</th>
                    <td class="text-center">${user['UserName']}</td>
                    <td class="text-center">${user['User']}</td>
                    <td class="text-center">${user['log']}</td>
                   
                `);
                tr.append(action);
                $('#tbdata').append(tr);
            });
            new DataTable('#tbshow', {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/lo.json',
                },
            });
        });
    }
    show();
</script>