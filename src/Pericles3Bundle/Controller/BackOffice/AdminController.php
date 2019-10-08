<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



 
class AdminController extends Controller
{


    private $output;
    private $em;
    
    public function SetEm($em)
    {
        $this->em=$em;
    }
    
    public function GetEm()
    {
        if ($this->em) { return($this->em); }
        else return($this->getDoctrine()->getManager());
    }
    
    public function SetOutput($output)
    {
        $this->output=$output;
    }
    
    public function GetOutput()
    {
        return($this->output);
    }
    
    public function Output($message)
    {
        if ($this->GetOutput()) { $this->GetOutput()->writeln($message);}
    }
    
    public function OutputOrFlash($message)
    {
        if ($this->GetOutput())
        { 
            $this->GetOutput()->writeln($message);
        }
        else
        {
            $this->AddFlash("success",$message);
        }
    }
    
    public function OutputOrFlashSuccess($message)
    {
        if ($this->GetOutput())
        { 
            $this->GetOutput()->writeln("<info>".$message."</info>");
        }
        else
        {
            $this->AddFlash("success",$message);
        }
    }
    
    public function OutputOrFlashError($message)
    {
        if ($this->GetOutput())
        { 
            $this->GetOutput()->writeln("<error>".$message."</error>");
        }
        else
        {
            $this->AddFlash("error",$message);
        }
    }
    
    
    
    public function AddFlashIf($type_message,$message)
    {
        if (! $this->GetOutput()) 
        { 
            $this->AddFlash($type_message,$message);
        }
    }
    
    
}
