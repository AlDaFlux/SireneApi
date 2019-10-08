<?php


namespace InDaFlux\SwissArmyKnifeBundle\Twig;

class SAKExtension extends \Twig_Extension 
{   

    public function getName()
    {
        return 'sakextension';
    }
    
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('progressBar', array($this, 'progressBar', ['is_safe' => ['html']])),
            new \Twig_SimpleFilter('progressBarNotCent', array($this, 'progressBarNotCent'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('GetIconFile', array($this, 'GetIconFile'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('HighlightText', array($this, 'HighlightText'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('formatBytes', array($this, 'formatBytes'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('hiddenFormatBytes', array($this, 'hiddenFormatBytes'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('hiddenDate', array($this, 'hiddenDate'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('TdSortableDate', array($this, 'TdSortableDate'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('limitChar', array($this, 'limitChar'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('similar_text', array($this, 'similar_text'), ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('similar_text_td', array($this, 'similar_text_td'), ['is_safe' => ['html']]),
             new \Twig_SimpleFilter('http_short', array($this, 'http_short'), ['is_safe' => ['html']]),
            );
    }


    

       
    
    public function http_short($url)
    {
        
        $reponse=parse_url($url, PHP_URL_SCHEME);
        $reponse.="://".parse_url($url, PHP_URL_HOST);
        if (parse_url($url, PHP_URL_PATH)) $reponse.="...";
        return ($reponse);
    }
    
    
    public function similar_text($value1,$value2)
    {
        return (similar_text($value1,$value2));
    }
    
    public function similar_text_td($value1,$value2)
    {
        $indice=similar_text($value1,$value2);

        if ($indice==101) $class="alert-success";
        elseif ($indice>90) $class="alert-info";
        elseif ($indice>60) $class="";
        elseif ($indice>20) $class="alert-warning";
        else $class="alert-danger";
        
        return (" <td class='".$class."' data-order='".$indice."'>".$indice." %</td>");
    }
    
    
    
    public function progressBar($progress)
    {
        if ($progress==100) $progress_bar_class="progress-bar-success";
        elseif ($progress==0) $progress_bar_class="progress-bar-danger";
        else $progress_bar_class="progress-bar-warning";
        
        $statut_rep= "<span class='hidden'>".str_pad($progress, 3, "0", STR_PAD_LEFT)."</span>";
        
        $statut_rep.=' <div class="progress">
                        <div class="progress-bar '.$progress_bar_class.'" role="progressbar" aria-valuenow="'.$progress.'"
                        aria-valuemin="0" aria-valuemax="100" style="width:'.$progress.'%">
                          <span  >'.$progress.'% Complète</span>
                        </div>
                      </div>';
        return ($statut_rep);
    }
    
    public function progressBarNotCent($val,$total)
    {
        $progress=round(($val/$total)*100,0,PHP_ROUND_HALF_DOWN);
        return ($this->progressBar($progress));
    }
    
    
    
    public function HighlightText($text,$word,$color="yellow")
    {
        $word_motif = '`(\b)('.$word.')(\b)`i';
        $word_out = '<span style=\'background-color:'.$color.';\'>'.$word.'</span>';
        $texteSurligne = preg_replace($word_motif, $word_out, $text);
        return($texteSurligne);
    }
    
    public function GetIconFile($file,$onlyicon=false)
    {
        switch (pathinfo($file, PATHINFO_EXTENSION))
        {
            case "pdf":
                  $icon='icon-file-pdf';
                break;
            case "doc":
            case "docx":
                  $icon='icon-file-word';
                break;
            case "xls":
            case "xlsx":
                  $icon='icon-file-excel';
                break;
            case "ptt":
                  $icon='icon-file-powerpoint';
                break;
            case "png":
            case "jpeg":
            case "jpg":
            case "gif":
                  $icon='icon-file-image';
                break;
            default:
                  $icon='icon-file-archive';
        }
        if ($onlyicon) return("<i class='".$icon."'></i>");        
        else return("<i class='".$icon."'></i>".$file);        
    }
    
     
    public function TdSortableDate($date,$format="d-m-Y", $class_if_null="alert-danger") 
    { 
        if (is_null($date))
        {
            return ("<td class='".$class_if_null."'></td>");
        }
        else
        {
            $hiden_date=$date->format('Y-m-d H:i:s');
            $date_visible=$date->format($format);
            return (" <td data-order='".$hiden_date."'>".$date_visible."</td>");
        }
    }
    
    
    public function hiddenDate($date,$format="d-m-Y") { 
        if (is_null($date))
        {
            return ("<span class='hidden'></span>");
        }
        else
        {
            $hiden_date=$date->format('Y-m-d H:i:s');
            $date_visible=$date->format($format);
            return ("<span class='hidden'>".$hiden_date."</span>".$date_visible);
        }
    }
    
    
    public function hiddenFormatBytes($size, $precision = 2) { 
            $hiden_bytes=str_pad($size, 15, "0", STR_PAD_LEFT);
            
            $bytes_formated=$this->formatBytes($size, $precision);
            return ("<span class='hidden'>".$hiden_bytes."</span>".$bytes_formated);
    }
    
    
    
    
    public function formatBytes($size, $precision = 1) { 
        if ($size >= 1073741824) 
            {
                $fileSize = round($size / 1024 / 1024 / 1024,$precision) . 'GB';
            }
            elseif ($size >= 1048576) 
            {
                $fileSize = round($size / 1024 / 1024,$precision) . 'MB';
            } 
            elseif($size >= 1024) {
                $fileSize = round($size / 1024,$precision) . 'KB';
        } 
        else 
        {
            $fileSize = $size . 'B';
        }
        return $fileSize;
    }
    
    
    public function limitChar($string,$nb,$strict=false)
    {
        if (strlen($string)<=$nb) return ($string);
        elseif ($strict)
        {
            return (substr($string,0,$nb)."…");
        }
        else
        {
            for ($i=$nb;$i>0;$i--)
            {
                if (substr($string,$i,1)==" ") 
                {
                    return (substr($string,0,$i)."…");
                }
            }
            return ($string);
        }
    }
    
    
    
}