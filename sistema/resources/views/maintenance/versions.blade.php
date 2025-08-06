@extends('adminlte::page')

@section('title', 'Histórico de versões')

@section('content_header')
<h1>Histórico de versões</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Histórico de versões</a></li>
</ol>
@endsection

@section('content')
    
    <div class="box box-default box-solid">
        <div class="box-header with-border"></div>
        <div class="box-body">

            <ul class="timeline">

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-gray">
                        20 Jan. 2020
                    </span>
                </li>
                <!-- /.timeline-label -->
            
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-chevron-down bg-teal"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 15:48</span>
            
                        <h3 class="timeline-header">
                            <a href="#">
                                v 3.0.0 beta
                            </a>
                        </h3>
            
                        <div class="timeline-body">
                            <p>- Publicação para testes em ambiente de produção.</p>
                        </div>
            
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-gray">
                        06 Ago. 2020
                    </span>
                </li>
                <!-- /.timeline-label -->
            
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-chevron-down bg-teal"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 15:06</span>
            
                        <h3 class="timeline-header">
                            <a href="#">
                                v 3.0.2
                            </a>
                        </h3>
            
                        <div class="timeline-body">
                            <p>- Atualização do framework para a versão 5.7.x.</p>
                        </div>
            
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-gray">
                        06 Ago. 2020
                    </span>
                </li>
                <!-- /.timeline-label -->
            
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-chevron-down bg-teal"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 18:01</span>
            
                        <h3 class="timeline-header">
                            <a href="#">
                                v 3.0.3
                            </a>
                        </h3>
            
                        <div class="timeline-body">
                            <p>- Atualização do framework para a versão 5.8.x.</p>
                        </div>
            
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-gray">
                        07 Ago. 2020
                    </span>
                </li>
                <!-- /.timeline-label -->
            
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-chevron-down bg-teal"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 14:17</span>
            
                        <h3 class="timeline-header">
                            <a href="#">
                                v 3.0.4
                            </a>
                        </h3>
            
                        <div class="timeline-body">
                            <p>- Atualização do framework para a versão 6.0.x.</p>
                        </div>
            
                    </div>
                </li>
                <!-- END timeline item -->

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-gray">
                        13 Ago. 2020
                    </span>
                </li>
                <!-- /.timeline-label -->
            
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-chevron-down bg-teal"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 09:27</span>
            
                        <h3 class="timeline-header">
                            <a href="#">
                                v 3.0.5
                            </a>
                        </h3>
            
                        <div class="timeline-body">
                            <p>- Botões que validam ou salvam dados, ao serem clicados serão bloqueados até o fim do processamento realizado pelo sistema.</p>
                            <p>- Verificação de conexão com intenet, através de mensagem de status (online/offline) ao lado da foto do perfil.</p>
                            <p>- Possibilidade de alterar a foto do perfil do usuário ao clicá-la.</p>
                            <p>- Outras correções e ajustes.</p>
                        </div>
            
                    </div>
                </li>
                <!-- END timeline item -->

            </ul>

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

@endsection
