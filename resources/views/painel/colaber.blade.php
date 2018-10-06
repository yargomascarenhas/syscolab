@extends("crudbooster::admin_template")
@section("content")

{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/ultimasNoticias.js', array('type' => 'text/javascript')) !!}

<div class="container-fluid">
  
<div class="row">
        
        

        

             
</div>


<div class="col-md-6">
  


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Primeiros Passos</h3>
        </div>

        <div class="box-body">

            <div class="btn-group-vertical btn-block">
                <a href="colaber"><button type="button" class="btn btn-default btn-block"><h4><i class="fa fa-list-alt"></i> Completar seus dados</h4></button></a>
                <a href="produtos/add"><button type="button" class="btn btn-default btn-block"><h4><i class="fa fa-plus"></i> Cadastrar seus produtos</h4></button></a>
                <a href="envios/add"><button type="button" class="btn btn-default btn-block"><h4><i class="fa  fa-th-large"></i> Nova Remessa de produtos</h4></button></a>
            </div>
        </div>
    </div>
  


  
  
  <div class="box" id="relatorios">
    <div class="box-header">
            <h3 class="box-title">Relatórios</h3>
        </div>
      
      <div class="box-body">
                
       
            
      @foreach($relatorios as $r)
          

        <div class="col-md-12">
        <form action="{{route('verRelatorio')}}" role="form" method="post">
           <input type="hidden" name="fromDT" value="{{date('m/d/Y', strtotime($r->fromDT))}}">
           <input type="hidden" name="toDT" value="{{date('m/d/Y', strtotime($r->toDT))}}">
           <input type="hidden" name="colaber" value="{{$r->colaber_id}}">
           <input type="hidden" name="porcentagem" value="{{$r->porcentagem}}">


           <button type="submit" class="btn btn-block btn-success btn-lg">{{date('d/m/Y', strtotime($r->fromDT))}} à {{date('m/d/Y', strtotime($r->toDT))}}</button>

        </form>
       
        
        <br>
        
      </div>
        @endforeach

         <div class="col-md-12">
        <a href="{{url('/admin/relatorio/'.CRUDBooster::myId().'')}}">
        <button type="button" class="btn btn-block btn-success btn-lg">Até 07/01/2018</button>
        </a>
        <br>
        
      </div>
        
      
      

      </div>
  </div>
     
     
  <div class="box box-warning" ng-app="syscolab" ng-controller="noticiasCtrl">
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title">Ultimas noticias</h3>
        </div>
        <div class="box-body">@{{log}}</div>
        @foreach($ultimasNoticias as $u)
        <div class="direct-chat-msg">
                  <div class="direct-chat-info clearfix">
                

                    
              <span class="direct-chat-timestamp pull-right">{{$u->created_at}}</span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img class="direct-chat-img" src="{{asset('/vendor/crudbooster/avatar.jpg')}}" alt="img"><!-- /.direct-chat-img -->
                  <div class="direct-chat-text">
                   {{$u->mensagem}}
                  </div>


                  <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->
          @endforeach
      </div>
  </div>





    
</div>

<div class="col-md-6">
        
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total de Vendas</span>
              <span class="info-box-number">R$ {{$totalVendas}}</span>
            </div>
            <!-- /.info-box-content -->

          </div>
          <!-- /.info-box -->

          
        

<div class="box">
            <div class="box-header">
              <h3 class="box-title">Produtos mais vendidos</h3>

              
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table">
                <tbody>
        
              @foreach($produtosMaisVendidos as $p)
                
                <tr>
                  <td>{{$p->venda_count}}x</td>
                  <td>{{$p->nome}}</td>
                  
                  <td><span class="badge bg-green">R$ {{$p->valor}}</span></td>
                </tr>
                @endforeach
               
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              
            </div>
          </div>
      


          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Produtos com estoque baixo</h3>

              
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table">
                <tbody>
                  <tr>
                  <th style="width: 10px">#</th>
                  <th>Produto</th>
                  <th>Qtde</th>
                  
                </tr>
        
  
                @foreach($produtosBaixoEstoque as $p)
                <tr>
                  <td>{{$p->id}}</td>
                  <td>{{$p->nome}}</td>
                  
                  <td><span class="badge bg-red">{{$p->entrada_estoque_count - $p->saida_estoque_count - $p->venda_count}}</span></td>
                </tr>
                @endforeach
               
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>

</div>

</div>

@endsection
