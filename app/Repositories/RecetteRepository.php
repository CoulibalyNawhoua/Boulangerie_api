<?php

namespace App\Repositories;

//use Your Model
use App\Models\FicheTechnique;
use App\Models\Produit;
use App\Models\Recette;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RecetteRepository extends Repository
{
    public function __construct(Recette $model)
    {
        $this->model = $model;
    }

    public function recetteStore(Request $request)
    {
        $recette = Recette::create([
            'name'=> $request->libelle,
            'prix_unitaire'=> $request->prix_unitaire,
            'added_by' => Auth::user()->id,
            'add_ip'=> $this->getIp()
        ]);

        $item_count=$request->item_count;
        if($item_count==0){
            //donnée vide

        }else{
            for ($i=0;$i<$item_count;$i++){

                $produit = Produit::where('id',$request->produits[$i])->first();

                FicheTechnique::create([
                    'quantity'=>$request->quantity[$i],
                    'produit_id'=>$produit->id,
                    'recette_id'=>$recette->id,
                    'groupe_unite_id'=>$request->unites[$i],
                    'unite_id'=>$produit->unites_id
                ]);
            }
        }
    }

    public function ListeRecettes()
    {
        return Recette::where('is_deleted',0)->with('auteur');
    }

    public function recetteView($id)
    {
        return Recette::where('id',$id)->firstOrFail();

    }

    public function recetteUpdate(Request $request, $id)
    {
        $recette = $this->model->find($id);

        $recette->update([
            'name'=> $request->libelle,
            'prix_unitaire'=> $request->prix_unitaire,
            'edited_by' => Auth::user()->id,
            'edit_ip'=> $this->getIp(),
            'edit_date'=>Carbon::now(),
        ]);

        $item_count=$request->item_count;
        if($item_count==0){
            //donnée vide

        }else{
            for ($i=0;$i<$item_count;$i++){

                $record = FicheTechnique::where('id', $request->fiche_item[$i])->first();

                $produit = Produit::where('id',$request->produits[$i])->first();

                if (is_null($record)){
                    FicheTechnique::create([
                        'quantity'=>$request->quantity[$i],
                        'produit_id'=>$request->produits[$i],
                        'recette_id'=>$recette->id,
                        'groupe_unite_id'=>$request->unites[$i],
                        'unite_id'=>$produit->unites_id
                    ]);
                }else{
                    $record->update([
                        'quantity'=>$request->quantity[$i],
                        'produit_id'=>$request->produits[$i],
                        'recette_id'=>$recette->id,
                        'groupe_unite_id'=>$request->unites[$i],
                        'unite_id'=>$produit->unites_id
                   ]);
                }

            }
        }
    }


    public function BilanProductRecette(Request $request)

    {
        $query  = FicheTechnique::where('recette_id', $request->recette_id)
                                ->leftJoin('recettes','recettes.id','=','fiches_techniques.recette_id')
                                ->leftJoin('produits','produits.id','=','fiches_techniques.produit_id')
                                ->leftJoin('groups_units','groups_units.id','=','fiches_techniques.groupe_unite_id')
                                ->selectRaw('recettes.name AS recette, produits.nom_produit AS produit, groups_units.name AS unite, fiches_techniques.*')
                                ->get();





        // $data = [];
        // foreach ($query as $item) {
        //     $data['recette'] = $item->recette;
        //     $data['produit'] = $item->produit;
        //     $data['unite'] = $item->unite;
        //     $data['quantity'] = $item->quantity;
        //     $data['total_quantity'] = (is_numeric($request->quantity_total)) ? $request->quantity_total * $item->quantity : 'Nombre invalide !' ;
        // }

        return $query;
    }

}
