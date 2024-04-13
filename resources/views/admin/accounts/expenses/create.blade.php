@extends('master')

@section('title')
    {{__('add').' '.__('expense')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('add')}} {{__('expense')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('expenses.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">{{__('title')}}/{{__('details')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="details" placeholder="{{__('title')}}/{{__('details')}}" name="details" required>{{ old('details') }}</textarea>
                                                    </div>
                                                    @if($errors->has('details'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('details') }}</p>
                                                    </div>
                                                @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row g-gs">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="date" required autocomplete="off">
                                                    </div>
                                                    @if($errors->has('date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('account') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg account-change" name="account" required>
                                                            <option value="">{{ __('select_parcel') }}</option>
                                                            @foreach($accounts as $account)
                                                                <option value="{{ $account->id }}">
                                                                    ({{__($account->method)}})
                                                                    @if($account->method == 'bank')
                                                                    {{$account->account_holder_name.', '. $account->account_no.', '. __($account->bank_name).','. $account->bank_branch}}.
                                                                    @elseif($account->method == 'cash')
                                                                    {{$account->user->first_name.' '. $account->user->last_name}}
                                                                    @else
                                                                        {{$account->account_holder_name.', '. $account->number.', '. __($account->type)}}
                                                                    @endif
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    <div class="nk-block-des text-success">
                                                        <p>{{ __('current_payable_balance') }}: <span id="current-balance"></span>{{__('tk')}}</p>
                                                        <input type="hidden" name="payable_balance" id="payable_balance">
                                                    </div>
                                                    @if($errors->has('account'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('amount')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control expense-amount" id="fv-full-name" value="{{ old('amount') }}" name="amount" required>
                                                    </div>
                                                    @if($errors->has('amount'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('amount') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="transaction_id">{{__('transaction_id')}} </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control expense-amount" id="transaction_id" value="{{ old('transaction_id') }}" name="transaction_id">
                                                    </div>
                                                    @if($errors->has('transaction_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('transaction_id') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="receipt">{{__('receipt')}}</label>
                                                    <div class="form-control-wrap">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="receipt" name="receipt">
                                                            <label class="custom-file-label" for="receipt">{{__('choose_file')}}</label>
                                                        </div>
                                                    </div>
                                                    @if($errors->has('receipt'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('receipt') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row">
                                            <div class="col-md-6 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('.account-change').on('change', function(){

            var token = "{{ csrf_token() }}";
            var url = "{{url('')}}"+'/admin/get-balance-info';

            var formData = {
                id: $(this).val()
            }

            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
            })
            .done(function(response) {
                console.log(response);

                if(response < 0){
                    $("#current-balance").parent().removeClass("text-success");
                    $("#current-balance").parent().addClass("text-danger");
                }else{
                    $("#current-balance").parent().removeClass("text-danger");
                    $("#current-balance").parent().addClass("text-success");
                }

                $("#current-balance").html(response);
                $("#payable_balance").val(response);

            })
            .fail(function(error) {
                Swal.fire('{{ __('opps') }}...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
            })

        });
    });

</script>

@endpush

