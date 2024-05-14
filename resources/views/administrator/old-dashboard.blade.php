@extends('layouts.admin-layout')

@section('content')
   
<div class="section">

  
    <div class="columns is-centered">
        <div class="column is-6">
            <div class="has-text-weight-bold is-size-4">ACCOUNTING</div>
            <div class="columns">
                <div class="column">
                
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">FUND SOURCES</div>

                        @foreach($fundSources as $fund)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $fund->fund_source }}
                            </div>
                            <div class="box-card-value">
                                {{ $fund->total_amount }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                    
                <div class="column">
                    
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">CURRENT FINANCIAL YEAR</div>
                        @foreach($cfy as $item)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $item->allotment_class }}
                            </div>
                            <div class="box-card-value">
                                {{ $item->amount}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BUDGETING -->
    <!-- BUDGETING -->
    <div class="columns is-centered">
        <div class="column is-6">
            <div class="has-text-weight-bold is-size-4">BUDGETING</div>
            <div class="columns">
                <div class="column">
                
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">FUND SOURCES</div>

                        @foreach($budgetingFundSources as $fund)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $fund->fund_source }}
                            </div>
                            <div class="box-card-value">
                                {{ $fund->total_amount }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                    
                <div class="column">
                    
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">CURRENT FINANCIAL YEAR</div>
                        @foreach($budgetingCurrentFY as $item)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $item->allotment_class }}
                            </div>
                            <div class="box-card-value">
                                {{ $item->amount}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- PROCUREMENT -->
    <div class="columns is-centered">
        <div class="column is-6">
            <div class="has-text-weight-bold is-size-4">PROCUREMENT</div>
            <div class="columns">
                <div class="column">
                
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">FUND SOURCES</div>

                        @foreach($procurementFundSources as $fund)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $fund->fund_source }}
                            </div>
                            <div class="box-card-value">
                                {{ $fund->total_amount }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                    
                <div class="column">
                    
                    <div class="box">
                        <div class="has-text-weight-bold is-size-5 mb-2">CURRENT FINANCIAL YEAR</div>
                        @foreach($procurementCurrentFY as $item)
                        <div class="box-card-container">
                            <div class="has-text-weight-bold box-card-title">
                                {{ $item->allotment_class }}
                            </div>
                            <div class="box-card-value">
                                {{ $item->amount}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    
</div>
@endsection
