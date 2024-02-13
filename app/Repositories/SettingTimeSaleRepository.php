<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Heure_vente;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreHoraireVenteRequest;

class SettingTimeSaleRepository extends Repository
{
    public function __construct(Heure_vente $model)
    {
        $this->model = $model;
    }


    public function store_setting_time_sale(StoreHoraireVenteRequest $request)
    {
       
        if($request->activate == 1){
            $ancienne_heure_defaut = Heure_vente::where('activate', 1)->first();

            if ($ancienne_heure_defaut) {
                $ancienne_heure_defaut->update([
                    'activate'=> 0,
                    'edit_ip' => $this->getIp(),
                    'edited_by' => Auth::user()->id,
                    'edit_date' => Carbon::now(),
                ]);

                Heure_vente::create([
                    'heure_debut'=>$request->heure_debut,
                    'heure_fin'=> $request->heure_fin,
                    'activate'=> $request->activate,
                    'add_ip' => $this->getIp(),
                    'added_by' => Auth::user()->id,
                ]);
            }
            else {
                Heure_vente::create([
                    'heure_debut'=>$request->heure_debut,
                    'heure_fin'=> $request->heure_fin,
                    'activate'=> $request->activate,
                    'add_ip' => $this->getIp(),
                    'added_by' => Auth::user()->id,
                ]);
            }
        }

        else {
            Heure_vente::create([
                'heure_debut'=>$request->heure_debut,
                'heure_fin'=> $request->heure_fin,
                'activate'=> $request->activate,
                'add_ip' => $this->getIp(),
                'added_by' => Auth::user()->id,
            ]);
        }

    }

    public function update_setting_time_sale(StoreHoraireVenteRequest $request, $id)
    {

        if($request->activate == 1){

            $ancienne_heure_defaut = Heure_vente::where('activate', 1)->first();

            if ($ancienne_heure_defaut) {

                $ancienne_heure_defaut->update([
                    'activate'=> 0,
                    'edit_ip' => $this->getIp(),
                    'edited_by' => Auth::user()->id,
                    'edit_date' => Carbon::now(),
                ]);
            }
            else {
                Heure_vente::where('id',$id)->update([
                    'activate'=> 1,
                    'heure_debut'=>$request->heure_debut,
                    'heure_fin'=> $request->heure_fin,
                    'edit_ip' => $this->getIp(),
                    'edited_by' => Auth::user()->id,
                    'edit_date' => Carbon::now(),
                ]);
            }

        }
        {
            Heure_vente::where('id',$id)->update([
                'activate'=> $request->activate,
                'heure_debut'=>$request->heure_debut,
                'heure_fin'=> $request->heure_fin,
                'edit_ip' => $this->getIp(),
                'edited_by' => Auth::user()->id,
                'edit_date' => Carbon::now(),
            ]);;
        }
    }
}
