<?php
namespace letId\assist
{
  abstract class essential
  {
    protected $Id;
    public function __construct($Id=null)
    {
      // $this->setId($Id);
      $this->Id = $Id;
    }
    public function setId($Id=null)
    {
      if ($Id) $this->Id = $Id;
      return $this;
    }
    // static function request($Id=null)
    // {
    //   return new self($Id);
    // }
  }
}