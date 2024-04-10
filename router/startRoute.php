<?php
if (!isset($_GET['page'])) {
    ?>
    <script>
        location.href = "?page=home";
    </script>
    <?php
}
