<?php
class CorrectModel
{
    private $no;
    private $sellID;
    private $billID;
    private $loterry2;
    private $loterry3;
    private $loterry4;
    private $loterry5;
    private $loterry6;
    private $amount;
    private $comment;

    public function __construct($no, $sellID, $billID, $loterry2, $loterry3, $loterry4, $loterry5, $loterry6, $amount, $comment)
    {
        $this->no = $no;
        $this->sellID = $sellID;
        $this->billID = $billID;
        $this->loterry2 = $loterry2;
        $this->loterry3 = $loterry3;
        $this->loterry4 = $loterry4;
        $this->loterry5 = $loterry5;
        $this->loterry6 = $loterry6;
        $this->amount = $amount;
        $this->comment = $comment;
    }

    public function getNo()
    {
        return $this->no;
    }

    public function setNo($no)
    {
        $this->no = $no;
    }

    public function getSellID()
    {
        return $this->sellID;
    }

    public function setSellID($sellID)
    {
        $this->sellID = $sellID;
    }

    public function getBillID()
    {
        return $this->billID;
    }

    public function setBillID($billID)
    {
        $this->billID = $billID;
    }

    public function getLoterry2()
    {
        return $this->loterry2;
    }

    public function setLoterry2($loterry2)
    {
        $this->loterry2 = $loterry2;
    }

    public function getLoterry3()
    {
        return $this->loterry3;
    }

    public function setLoterry3($loterry3)
    {
        $this->loterry3 = $loterry3;
    }

    public function getLoterry4()
    {
        return $this->loterry4;
    }

    public function setLoterry4($loterry4)
    {
        $this->loterry4 = $loterry4;
    }

    public function getLoterry5()
    {
        return $this->loterry5;
    }

    public function setLoterry5($loterry5)
    {
        $this->loterry5 = $loterry5;
    }

    public function getLoterry6()
    {
        return $this->loterry6;
    }

    public function setLoterry6($loterry6)
    {
        $this->loterry6 = $loterry6;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}