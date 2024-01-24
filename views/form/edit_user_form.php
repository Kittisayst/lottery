<?php if (isset($_GET['id'])) : ?>
    <?php
    require_once("./database/getUserByID.php");
    $user = getUserData();
    ?>
    <div class="container content">
        <?php require_once("./views/Alert.php") ?>
        <form class="bg-body-tertiary p-5 rounded" id="formUser">
            <div class="mb-3">
                <label for="" class="form-label">ຊື່ຜູ້ໃຊ້ງານ</label>
                <input type="text" class="form-control" name="UserName" placeholder="ຊື່ຜູ້ໃຊ້" value="<?= $user['UserName'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">User</label>
                <input type="text" class="form-control" name="User" placeholder="User" value="<?= $user['User'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input type="password" class="form-control" name="Password" placeholder="Password">
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary w-100" type="submit">ແກ້ໄຂຜູ້ໃຊ້ງານ</button>
                <a href="?page=user" class="btn btn-warning w-100">ກັບຄືນ</a>
            </div>
        </form>
    </div>
    <script>
        const formuser = $("#formUser");
        formuser.on('submit', (e) => {
            e.preventDefault();
            const formData = formuser.serialize();
            $.post(`./api/userAPI.php?api=update&id=<?= $user['userID'] ?>`, formData, (result) => {
                if (result.state) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).finally(() => {
                        location.href = "?page=user";
                    });
                }
            })
        });
    </script>
<?php else : ?>
    <script>
        location.href = "?page=user";
    </script>
<?php endif; ?>