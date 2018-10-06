<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Relatorio;
use App\Models\RelatorioItem;
use App\Models\Vendas;
use App\Models\VendasItem;
use App\Models\Colaber;
use App\Models\Produtos;
use CRUDBooster;
use Carbon\Carbon;

class RelatoriosController extends Controller
{
    public function index()
    {
        
        $colabers = User::where('id_cms_privileges',2)->orderBy('name')->get();
        
        $porcentagem = array(

            30 => '30%',
            20 => '20%',
            10 => '10%',
            40 => '40%',
            50 => '50%'
            
        );


        $lista = Relatorio::with('colaber')->orderBy('created_at','desc')->paginate(20);


        // dd($lista);

        return view('relatorios.lista')
                ->with([
                    'colabers'=>$colabers,
                    
                    'porcentagem'=>$porcentagem,
                    'relatorios'=>$lista

                ]);
    }

    public function gerarRelatorio(Request $request)
    {
    	
        $id = $request->input('colaber');
        
        $fromDT = Carbon::createFromFormat('m/d/Y H',$request->input('fromDT').'00')->toDateTimeString();
        $toDT = Carbon::createFromFormat('m/d/Y H',$request->input('toDT').'00')->toDateTimeString();

        
        $porcentagem = $request->input('porcentagem');


        $colaber = Colaber::where('user_id',$id)->first();

        
        $produtos = Produtos::where('user_id',$id)->get();   


        foreach($produtos as $p)
        {
            $listaProdutos[] = $p->id;
        }



        $vendas = VendasItem::where('id','>',2109)
                    ->whereIn('produto_id',$listaProdutos)
                    ->whereBetween('created_at',[$fromDT,$toDT])
                    ->with('produto')
                    ->orderBy('created_at','asc')
                    
                    ->get();




       $periodo = date('d/m/Y',strtotime($fromDT)).' a '.date('d/m/Y',strtotime($toDT));

      
            // Check if user is equal

        $this->salvarRelatorio($colaber,$porcentagem,$fromDT,$toDT);



        return view('relatorios.venda')->with(['lista'=>$vendas, 'colaber'=>$colaber,'porcentagem'=>$porcentagem,'periodo'=>$periodo]);
    }


    public function salvarRelatorio($colaber,$porcentagem,$fromDT,$toDT)
    {	


        $relatorio = new Relatorio;

        $relatorio->user_id = CRUDBooster::myId();
        $relatorio->active = 0;
        $relatorio->colaber_id = $colaber->user_id;
        $relatorio->porcentagem = $porcentagem;
        $relatorio->fromDT = $fromDT;
        $relatorio->toDT = $toDT;

        $relatorio->save();

        return $relatorio;  	

    }

    public function ativarRelatorio($id)
    {
        $relatorio = Relatorio::find($id);

        $relatorio->active = 1;
        $relatorio->save();


        $periodo = date('d/m/Y',strtotime($relatorio->fromDT)).' a '.date('d/m/Y',strtotime($relatorio->toDT));


        $notification = CRUDBooster::sendNotification($config=[
                'content'=>'Seu relatório de '.$periodo.' está disponível',
                 'to'=> 'admin/painel#relatorios',
                 'id_cms_users'=>[$relatorio->colaber_id]]);

        

        return redirect()->route('relatorios')->with(['message'=>'Agora o colaber pode visualizar esse relatório!','message_type'=>'success']);

    }

     public function desativarRelatorio($id)
    {
        $relatorio = Relatorio::find($id);

        $relatorio->active = 0;
        $relatorio->save();

        return redirect()->route('relatorios')->with(['message'=>'O Colaber não tem mais acesso a esse relatório','message_type'=>'success']);

    }

   
    public function verRelatorio(Request $request)
    {
        

        $id = $request->input('colaber');


        // dd($request->all());
        
        $fromDT = Carbon::createFromFormat('m/d/Y H',$request->input('fromDT').'00')->toDateTimeString();
        $toDT = Carbon::createFromFormat('m/d/Y H',$request->input('toDT').'00')->toDateTimeString();



        
        $porcentagem = $request->input('porcentagem');


        $colaber = Colaber::where('user_id',$id)->first();

        
        $produtos = Produtos::where('user_id',$id)->get();   


        foreach($produtos as $p)
        {
            $listaProdutos[] = $p->id;
        }



        $vendas = VendasItem::where('id','>',2109)
                    ->whereIn('produto_id',$listaProdutos)
                    ->whereBetween('created_at',[$fromDT,$toDT])
                    ->with('produto')
                    ->orderBy('created_at','asc')
                    
                    ->get();




       $periodo = date('d/m/Y',strtotime($fromDT)).' a '.date('d/m/Y',strtotime($toDT));

      
            // Check if user is equal

        // $this->salvarRelatorio($colaber,$porcentagem,$fromDT,$toDT);



        return view('relatorios.venda')->with(['lista'=>$vendas, 'colaber'=>$colaber,'porcentagem'=>$porcentagem,'periodo'=>$periodo]);
    }



    public function delete($id)
    {
        $relatorio = Relatorio::find($id);
        $relatorio->delete();

        return redirect()->route('relatorios')->with(['message'=>'Relatorio Excluido com sucesso','message_type'=>'success']);
    }



    public function relatorioVendas()
    {
        
            $vendas = Vendas::orderBy('created_at','asc')->get();
            $itens = VendasItem::all();


            return view('vendas.relatorio')
                    ->with([
                        'lista'=>$vendas,
                        'itens'=>$itens,
                    ]);            
    }


    public function relatorioCompleto(Request $request)
    
    {
        
        
        $daterange = explode(' - ', $request->input('daterange'));


        $fromDT = $daterange[0];
        $toDT = $daterange[1];

       


        $fromDT = Carbon::createFromFormat('m/d/Y H',$fromDT.'00')->toDateTimeString();
        $toDT = Carbon::createFromFormat('m/d/Y H',$toDT.'00')->toDateTimeString();






        // dd($fromDT);

        $vendas = Vendas::with('itens')
                    // ->take(15)
                    // ->whereDate('created_at','>=', $fromDt)
                    // ->whereDate('created_at','<=', $toDt)
                    ->where('id','>',1975)
                    ->whereBetween('created_at', [$fromDT,$toDT])
                    ->orderBy('created_at','asc')
                    ->get();
      
      // dd($vendas);


        $periodo = date('d/m/Y',strtotime($fromDT)).' a '.date('d/m/Y',strtotime($toDT));            

        

         return view('relatorios.completo')->with(['vendas'=>$vendas,'periodo'=>$periodo]);

    }

    


    public function verPdf($id)
    {

            $file = \Storage::disk('public')->get('relatorios/'.$id.'.pdf');


            return response($file)->withHeaders(['Content-Type'=>'application/pdf']);
    }



   
}
