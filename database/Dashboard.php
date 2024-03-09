<?php
function getDebtCount()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT COUNT(FinancialID) AS lotCount FROM tb_financail WHERE state=0";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $resutl = $stmt->fetchAll();
    return $resutl[0]['lotCount'];
}

function getDebtModalDialog()
{
    require_once("./database/connectDB.php");
    $connnect = new connectDB();
    $db = $connnect->getConnection();
    $sql = "SELECT * FROM tb_financail
    INNER JOIN tb_unit ON tb_financail.UnitID = tb_unit.unitID
    INNER JOIN tb_province ON tb_unit.provinceID = tb_province.pid
    WHERE state=0 GROUP BY tb_financail.UnitID ORDER BY FinancialID DESC LIMIT 50";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $resutl = $stmt->fetchAll();
    if ($stmt->rowCount()>0) {
        foreach ($resutl as $row) {
           echo '<a href="?page=financialhistory&unitid='.$row['unitID'].'&pid='.$row['provinceID'].'&search=" class="list-group-item list-group-item-action d-flex justify-content-between" aria-current="true">
           <spn>'.$row['unitName'].'</spn>
           <spn class="badge bg-secondary">'.$row['pname'].'</spn>
           </a>';
        }
    }else{

    }
}

?>
