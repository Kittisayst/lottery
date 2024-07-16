<div class="container content">
    <?php require_once ("./views/Alert.php") ?>
    <div class="d-flex justify-content-between mb-3">
        <div>
            <a href="?page=addpermission" class="btn btn-primary">ເພີ່ມສິດທິ</a>
        </div>
        <form id="frmsearch">
            <div class="d-flex justify-content-between gap-2">

            </div>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr class="text-center align-middle">
                <th scope="col">ລ/ດ</th>
                <th scope="col">ສິດທິ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            require_once "./database/PermissionTable.php";
            ?>
        </tbody>
    </table>
</div>