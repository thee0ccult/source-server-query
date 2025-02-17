@extends('layouts.simple')

@section('content')
    <!-- Hero -->
    <div class="bg-white bg-pattern" style="background-image: url('{{ asset('/media/various/bg-pattern-inverse.png') }}');">
        <div class="hero overflow-hidden">
            <div class="hero-inner">
                <div class="content content-full text-center">
                    <div class="pt-100 pb-100">
                        <h1 class="font-w700 display-4 mt-20 invisible" data-toggle="appear" data-timeout="50">
                            Codebase
                            <span class="font-w300">+</span>
                            Laravel <span class="font-w300 text-pulse">8</span>
                        </h1>
                        <h2 class="h3 font-w400 text-muted mb-50 invisible" data-toggle="appear" data-class="animated fadeInDown" data-timeout="300">
                            Welcome to the starter kit! Build something amazing!
                        </h2>
                        <div class="invisible" data-toggle="appear" data-class="animated fadeInUp" data-timeout="300">
                            <a class="btn btn-hero btn-alt-primary min-width-175 mb-10 mx-5" href="/dashboard">
                                <i class="fa fa-fw fa-arrow-right mr-1"></i> Enter Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Hero -->
@endsection
