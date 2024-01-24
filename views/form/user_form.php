<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <form class="bg-body-tertiary p-5 rounded" id="formUser">
        <div class="mb-3">
            <label for="" class="form-label">ຊື່ຜູ້ໃຊ້ງານ</label>
            <input type="text" class="form-control" name="UserName" placeholder="ຊື່ຜູ້ໃຊ້" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">User</label>
            <input type="text" class="form-control" name="User" placeholder="User" required>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Password</label>
            <input type="password" class="form-control" name="Password" placeholder="Password" required>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary w-100" type="submit">ເພີ່ມຜູ້ໃຊ້ງານ</button>
            <a href="?page=user" class="btn btn-warning w-100">ກັບຄືນ</a>
        </div>
    </form>
</div>
<script src="./script/createUser.js"></script>