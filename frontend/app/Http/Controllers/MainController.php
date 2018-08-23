<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MPA;
use View;

class MainController extends Controller
{
    public function index() {
    	$mpas[0] = new MPA();
    	$mpas[0]->name = 'Arrábida';
    	$mpas[0]->map = 'arrabida.png';
    	$mpas[0]->area = 52.8;
    	$mpas[0]->year = 1998;
    	$mpas[1] = new MPA();
    	$mpas[1]->name = 'Ria Formosa';
    	$mpas[1]->map = 'ria_formosa.png';
    	$mpas[1]->area = 120.3;
    	$mpas[1]->year = 1978;
    	$mpas[2] = new MPA();
    	$mpas[2]->name = 'Santo André e Sancha';
    	$mpas[2]->map = 'santo_andre_e_sancha.png';
    	$mpas[2]->area = 21.5;
    	$mpas[2]->year = 1988;
    	$mpas[3] = new MPA();
    	$mpas[3]->name = 'Sudoeste Alentejano';
    	$mpas[3]->map = 'sudoeste_alentejano.png';
    	$mpas[3]->area = 290.2;
    	$mpas[3]->year = 1988;

    	$data['mpas'] = $mpas;
    	return View::make('index', $data);
    }

    public function arrabida() {
    	$mpa = new MPA();
    	$mpa->name = 'Arrábida';
    	$mpa->map = 'arrabida.png';
    	$mpa->area = 52.8;
    	$mpa->year = 1998;
    	for ($i=0; $i < 24; $i++) { 
    		$mpa->threat_levels[$i] = rand(0, 15);
    	}
    	$mpa->event_highlights[0][0] = 'Boat detected by BuoyX20OIM';
    	$mpa->event_highlights[0][1] = '17/08/2018 16:09';
    	$mpa->event_highlights[1][0] = 'Boat detected by BuoyA62OIM';
    	$mpa->event_highlights[1][1] = '01/07/2018 12:01';
    	$mpa->event_highlights[2][0] = 'Boat detected by BuoyL54OIM';
    	$mpa->event_highlights[2][1] = '11/06/2018 19:12';
    	$mpa->event_highlights[3][0] = 'Boat detected by BuoyC78OIM';
    	$mpa->event_highlights[3][1] = '08/06/2018 15:34';
    	$data['mpa'] = $mpa;
    	return View::make('mpa', $data);
    }

   	public function ria_formosa() {
   		$mpa = new MPA();
   		$mpa->name = 'Ria Formosa';
   		$mpa->map = 'ria_formosa.png';
   		$mpa->area = 120.3;
   		$mpa->year = 1978;
   		for ($i=0; $i < 24; $i++) { 
    		$mpa->threat_levels[$i] = rand(0, 100);
    	}
    	$data['mpa'] = $mpa;
    	return View::make('mpa', $data);
   	}

   	public function santo_andre_e_sancha() {
   		$mpa = new MPA();
    	$mpa->name = 'Santo André e Sancha';
    	$mpa->map = 'santo_andre_e_sancha.png';
    	$mpa->area = 21.5;
    	$mpa->year = 1988;
    	for ($i=0; $i < 24; $i++) { 
    		$mpa->threat_levels[$i] = rand(0, 100);
    	}
    	$data['mpa'] = $mpa;
    	return View::make('mpa', $data);
   	}

   	public function sudoeste_alentejano() {
   		$mpa = new MPA();
    	$mpa->name = 'Sudoeste Alentejano';
    	$mpa->map = 'sudoeste_alentejano.png';
    	$mpa->area = 290.2;
    	$mpa->year = 1988;
    	for ($i=0; $i < 24; $i++) { 
    		$mpa->threat_levels[$i] = rand(0, 100);
    	}
    	$data['mpa'] = $mpa;
    	return View::make('mpa', $data);
   	}
}
