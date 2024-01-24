<div class="container content">
    <?php require_once("./views/Alert.php") ?>
    <table class="table table-bordered" id="tbshow">
        <thead class="table-light">
            <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">ແຂວງ</th>
                <th scope="col">ຈັດການ</th>
            </tr>
        </thead>
        <tbody id="tbdata">
            <?php
            require_once("./database/provinceDB.php");
            $index = 1;
            foreach (getProvinceTable() as $row) {
            ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td><?= $row['pname'] ?></td>
                    <td>
                        <a href="?page=province&id=<?= $row['pid'] ?>" class="btn btn-success btn-sm"><i class='bx bxs-edit'></i></a>
                        <button href="#" class="btn btn-danger btn-sm"><i class='bx bxs-trash'></i></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            <script>
                new DataTable('#tbshow', {
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/lo.json'
                    },
                    "lengthMenu": [
                        [10, 25, 50, 75, 100, -1],
                        [10, 25, 50, 75, 100, "ທັງໝົດ"]
                    ],
                    "pageLength": -1
                });
            </script>
        </tbody>
    </table>
</div>