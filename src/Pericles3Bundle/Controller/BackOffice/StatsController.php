<?php

namespace Pericles3Bundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


use fados\ChartjsBundle\Model\ChartBuiderData;
use fados\ChartjsBundle\Utils\TypeCharjs;
use fados\ChartjsBundle\Utils\TypeColors;
use fados\ChartjsBundle\Model\ChartData;

use \Datetime;


/**
 * Gestionnaire controller.
 *
 * @Route("/backoffice/stats")
 */ 
class StatsController extends Controller
{
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/", name="arsene_stats")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $creais = $em->getRepository('Pericles3Bundle:Creai')->findAll();
        $referentielsPublic = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        $NbSansCreai = $em->getRepository('Pericles3Bundle:Etablissement')->findNbReelSansCreai();
        $NbGestionnaireSansCreai = $em->getRepository('Pericles3Bundle:Gestionnaire')->findNbReelSansCreai();
        return $this->render('BackOffice/Stats/index.html.twig', 
                array(
                    'creais' => $creais,
                    'NbSansCreai' => $NbSansCreai,
                    'NbGestionnaireSansCreai' => $NbGestionnaireSansCreai,
                    
                    'referentielsPublics' => $referentielsPublic,
                     'grafic_bypublic'=> $this->getGraphByPublic(),
                     'grafic_bycreai'=> $this->getGraphByCreai()
                ));
    }
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/test", name="arsene__teststats")
     * @Method("GET")
     */
    public function indexTestAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $grafica = new ChartBuiderData();
        $grafica->setType(TypeCharjs::CHARJS_BAR);
        
        $creais = $em->getRepository('Pericles3Bundle:Creai')->findAll();
        {
            
            $labels =array();
            foreach ($creais as $creai)
            {
                $labels[]=$creai->GetNom();
            }
        }
        $grafica->setLabels($labels);
        $grafica->setData(
            array(
                'Profit' => array(23,45,65,12,34,45,88,23,45,65,12,34,45,88,55),
                'Cost' => array(13,34,54,11,34,35,48,45,65,12,34,45,88,55),
                'Ravenue'=> array(5,7,10,12,5,1,4,45,65,12,34,45,88,55),

            ));
        $grafica->setBackgroundcolor($this->GetDefaultColors());
        $grafica->setBordercolor($this->GetDefaultColors());
        $grafica->setOptions('
                    title:{
                         display:true,
                        text:"Chart.js Bar Chart - Stacked"
                    },
                    tooltips: {
                        mode: "index",
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
        ');
        $grafica->setBackgroundOpacity(0.7);
        $grafica->setHeight('150px');
        $grafica->setWidth('500px');
 
        $grafica->setTitle('Sample Charjs Bar');


        return $this->render('BackOffice/Stats/graph_test.html.twig',array('grafica'=>$grafica,'title'=>$grafica->getTitle()));
   
    }
    
    
    public function DefaultOptionsStacked($title)
    {
        return('title:{
                         display:true,
                        text:"'.$title.'"
                    },
                    tooltips: {
                        mode: "index",
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
        ')  ; 
    }
    
    
    function grafica_stacked($title,$labels,$datas,$colors=null)
    {
        $grafica = new ChartBuiderData($labels);
        $grafica->setType(TypeCharjs::CHARJS_BAR);
        $grafica->setLabels($labels);
        $grafica->setData($datas);
        if ($colors) $grafica->setBackgroundcolor($colors);
        else $grafica->setBackgroundcolor($this->GetDefaultColors());
        
        
        
        $grafica->setBordercolor($this->GetDefaultColors());
        $grafica->setOptions($this->DefaultOptionsStacked($title));
        $grafica->setBackgroundOpacity(0.7);
        $grafica->setHeight('150px');
        $grafica->setWidth('500px');
        $grafica->setTitle($title);

        return($grafica);
    }
    
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/ventes_par_mois", name="ventes_par_mois")
     * @Method("GET")
     */
    public function indexVentesParMoisAction()
    {
        $graficas =array();
        $em = $this->getDoctrine()->getManager();
         
        $date_debut = new DateTime('2017-01-01');
        $date_fin = new DateTime();
//        $date_fin ->modify('+1 year ');
        $labels =array();
        $facturees_ren_non_payees =array();
        $facturees_ren_payees =array();
        $facturees_non_payees =array();
        $facturees_payees =array();
        $factures_par_mois =array();
        for ($date_i=$date_debut;$date_i<$date_fin;$date_i->modify('+1 month'))
        {
                $date_2= clone $date_i;
                $date_2->modify('+1 month');
                $labels[]=$date_i->format('Y-m');
                $facturees_ren_non_payees[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeStillDate($date_2,false,true)['total'];
                $facturees_ren_payees[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeStillDate($date_2,true,true)['total'];
                $facturees_non_payees[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeStillDate($date_2,false,false)['total'];
                $facturees_payees[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeStillDate($date_2,true,false)['total'];

//                $factures_montant[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeFactureeNonPayeBetweenDate($date_i,$date_2)['total'];
                $factures_par_mois_non_payes[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeFactureeNonPayeBetweenDate($date_i,$date_2)['total'];
                $factures_par_mois_payee[]=$em->getRepository('Pericles3Bundle:FacturePresta')->findSommeFactureePayeBetweenDate($date_i,$date_2)['total'];
        }
        $datas=array('Renouvellement' => $facturees_ren_payees,'Renouvellement- En attente de paiement' => $facturees_ren_non_payees,'1er abonnement - Payées' => $facturees_payees,'1er abonnement - En attente de paiement' => $facturees_non_payees);
        $graficas[]=$this->grafica_stacked("Montant des licences facturées (cumulées)",$labels,$datas,array(TypeColors::dark_orange,TypeColors::salmon,TypeColors::navy,TypeColors::turquoise));
        

        $datas=array("Payées" =>  $factures_par_mois_payee,'A Payer' => $factures_par_mois_non_payes,);
        
        $graficas[]=$this->grafica_stacked("Montant des licences facturées et payées (par mois)",$labels,$datas);

        return $this->render('BackOffice/Stats/graph_test.html.twig',array('graficas'=>$graficas,'title'=>'Graphiques'));
   
    }
    
    
    
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/creai_{id}", name="arsene_stats_creai")
     * @Method("GET")
     */
    public function indexCreaiAction(\Pericles3Bundle\Entity\Creai $creai)
    {
        
        return $this->render('BackOffice/Stats/creai.html.twig', 
                array(
                    'grafic_bypublic'=> $this->getGraphByPublic(),
                    'creai' => $creai,
                ));
    }
    
    
    
    
    /**
     * Lists all Gestionnaire entities.
     *
     * @Route("/connection_user", name="arsene_stats_connections")
     * @Method("GET")
     */
    public function indexUserConnectAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stats_day = $em->getRepository('Pericles3Bundle:StatUserConnect')->findByDay(60);
        $stats_day_week = $em->getRepository('Pericles3Bundle:StatUserConnect')->findByDayOfTheWeek();
        $stats_month= $em->getRepository('Pericles3Bundle:StatUserConnect')->findByMonth(6);
        $stats_hour= $em->getRepository('Pericles3Bundle:StatUserConnect')->findByHour();
        
        return $this->render('BackOffice/Stats/connections_user.html.twig', array('stats_day'=>$stats_day,'stats_month'=>$stats_month,'stats_hour'=>$stats_hour,'stats_day_week'=>$stats_day_week));
    }
    
    
    
    public function getGraphPie($datas,$labels,$lib="nb")
    {
        
        $grafica = new ChartBuiderData();
        $grafica->setType(TypeCharjs::CHARJS_PIE);
        $grafica->setLabels($labels);
        $grafica->setData(
          array(
              $lib => $datas
          ));
        
        $grafica->setBackgroundcolor($this->GetDefaultColors());
        $grafica->setBordercolor($this->GetDefaultColors());
        $grafica->setHeight('500px');
        $grafica->setWidth('500px');
        $grafica->setBackgroundOpacity(0.7); 
        return($grafica);
    }
    
    
    public function getGraphByPublic()
    {
        
        $em = $this->getDoctrine()->getManager();
        $referentielsPublic = $em->getRepository('Pericles3Bundle:ReferentielPublic')->findAll();
        $labels =array();
        $datas =array();
        foreach ($referentielsPublic as $referentielPublic)
        {
            $labels[]=$referentielPublic->GetPublic();
            $datas[]=$referentielPublic->GetNbEtablissementsReelsCascade();
        }
        $grafica=$this->getGraphPie($datas,$labels,$lib="Nb Etablissements");
        return($grafica);
    }
    
    
    public function getGraphByCreai()
    {
        
        $em = $this->getDoctrine()->getManager();
        $creais = $em->getRepository('Pericles3Bundle:Creai')->findAll();
        $labels =array();
        $datas =array();
        foreach ($creais as $creai)
        {
            $labels[]=$creai->GetNom();
            $datas[]=$creai->GetNbEtablissementsReels();
        }
        $grafica=$this->getGraphPie($datas,$labels,$lib="Nb ref");
        return($grafica);
    }
    


    
    public function GetDefaultColors()
    {
        
       return(array(
TypeColors::navy,
TypeColors::turquoise,
TypeColors::dark_red,
TypeColors::blue_violet,
TypeColors::light_golden_rod_yellow,
TypeColors::orange_red,
TypeColors::light_pink,
TypeColors::steel_blue,
TypeColors::brown,
TypeColors::yellow,
TypeColors::light_sea_green,
TypeColors::wheat,
TypeColors::aqua,
TypeColors::saddle_brown,
TypeColors::dark_slate_gray,
TypeColors::light_blue,
TypeColors::corn_flower_blue,
TypeColors::dark_salmon,
TypeColors::olive_drab,
TypeColors::linen,
TypeColors::white_smoke,
TypeColors::chocolate,
TypeColors::powder_blue,
TypeColors::golden_rod,
TypeColors::khaki,
TypeColors::dark_blue,
TypeColors::indigo,
TypeColors::blue,
TypeColors::lime,
TypeColors::sienna,
TypeColors::medium_blue,
TypeColors::dodger_blue,
TypeColors::medium_purple,
TypeColors::medium_orchid,
TypeColors::dark_violet,
TypeColors::dark_golden_rod,
TypeColors::medium_aqua_marine,
TypeColors::dark_slate_blue,
TypeColors::purple,
TypeColors::silver,
TypeColors::old_lace,
TypeColors::deep_pink,
TypeColors::firebrick,
TypeColors::dark_green,
TypeColors::orange,
TypeColors::sky_blue,
TypeColors::violet,
TypeColors::light_steel_blue,
TypeColors::azure,
TypeColors::magenta_,
TypeColors::navajo_white,
TypeColors::ghost_white,
TypeColors::light_yellow,
TypeColors::green_yellow,
TypeColors::pale_golden_rod,
TypeColors::coral,
TypeColors::medium_spring_green,
TypeColors::dark_orange,
TypeColors::green,
TypeColors::dark_magenta,
TypeColors::salmon,
TypeColors::indian_red,
TypeColors::misty_rose,
TypeColors::ivory,
TypeColors::thistle,
TypeColors::slate_gray,
TypeColors::dark_sea_green,
TypeColors::dark_cyan,
TypeColors::moccasin,
TypeColors::gainsboro,
TypeColors::sea_shell,
TypeColors::sandy_brown,
TypeColors::antique_white,
TypeColors::blanched_almond,
TypeColors::lemon_chiffon,
TypeColors::olive,
TypeColors::dim_gray,
TypeColors::light_sky_blue,
TypeColors::peach_puff,
TypeColors::orchid,
TypeColors::light_gray,
TypeColors::plum,
TypeColors::papaya_whip,
TypeColors::mint_cream,
TypeColors::aqua_marine,
TypeColors::pale_turquoise,
TypeColors::teal,
TypeColors::alice_blue,
TypeColors::dark_olive_green,
TypeColors::red,
TypeColors::light_coral,
TypeColors::rosy_brown,
TypeColors::royal_blue,
TypeColors::slate_blue,
TypeColors::dark_khaki,
TypeColors::dark_orchid,
TypeColors::deep_sky_blue,
TypeColors::floral_white,
TypeColors::cadet_blue,
TypeColors::medium_turquoise,
TypeColors::light_salmon,
TypeColors::sea_green,
TypeColors::lawn_green,
TypeColors::corn_silk,
TypeColors::light_cyan,
TypeColors::beige,
TypeColors::cyan,
TypeColors::medium_slate_blue,
TypeColors::dark_grey,
TypeColors::pale_violet_red,
TypeColors::crimson,
TypeColors::midnight_blue,
TypeColors::black,
TypeColors::peru,
TypeColors::medium_violet_red,
TypeColors::yellow_green,
TypeColors::bisque,
TypeColors::chart_reuse,
TypeColors::gray,
TypeColors::tomato,
TypeColors::maroon,
TypeColors::hot_pink,
TypeColors::snow,
TypeColors::light_slate_gray,
TypeColors::burly_wood,
TypeColors::lavender_blush,
TypeColors::dark_turquoise,
TypeColors::medium_sea_green,
TypeColors::lavender,
TypeColors::white,
TypeColors::honeydew,
TypeColors::lime_green,
TypeColors::pink,
TypeColors::forest_green,
TypeColors::light_green,
TypeColors::tan,
TypeColors::spring_green,
TypeColors::gold,
TypeColors::pale_green,


               ));
        
    }
    
    
}   


