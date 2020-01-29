@extends('layouts.main')

@section('content')
<section class="heading_top create">
        <div class="container">
            <h2>Create an Account</h2>
            <p>Already have an account? Login
        </div>
    </section>
    
    <!-- Start Team section-->
    <section class="bepart_bx create">
       <div class="container">
           
        <div class="row ">
            <div class="border_bx_acc create">
             <form method="POST" action="{{ route('register_user') }}">
                @csrf  
                <div class="col-md-12">
                    <!-- Tab panes -->
                    <div class="border_bx">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" placeholder="Karen" class="form-control" name="first_name"  required />
                                </div>
                            
                            </div>
                            <div class="col-md-6">                      
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" placeholder="Marrs" class="form-control" name="last_name" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="karen@gmail.com" class="form-control"  required/>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" placeholder="******" class="form-control"  required/>
                                </div>
                            </div>  
                        </div>  
                    </div>  
                            
                    <h2>Service Address</h2>    
                    
                    <div class="border_bx">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control"  required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City, State</label>
                                    <input type="text" name="city_state" class="form-control"  required/>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zip Code</label>
                                    <input type="text" name="zip" class="form-control"  required/>
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <ul class="list_hm">
                                        <li><label><input type="checkbox" name="services[]" value="Home"/> Home</label></li>
                                        <li><label><input type="checkbox" name="services[]" value="Apartment"/> Apartment</label></li>                                      <li><label><input type="checkbox" name="services[]" value="Condo" /> Condo</label></li>                                          
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cell Phone Number</label>
                                    <input type="text" class="form-control"  required name="phone" />
                                </div>  
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>How did you hear about us!</label>
                                    <select class="form-control" name="heared"  required>
                                        <option value="Select all that apply">Select all that apply</option>
                                    </select>
                                </div>      
                            </div>
                        </div>  
                    </div>  
                    <h2>Agree to the terms and conditions</h2>
                    <label>
                    <input type="checkbox" name="check" required />
                    By clicking this button, I agree to Cesta's terms & conditions and cancellation policy.
                    </label>
                    <h2>Enter Payment Information</h2>
                    
                    <div class="border_bx">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cardholder's Name* </label>
                                    <input type="text" class="form-control"  name="card_name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Credit Card Number*</label>
                                    <input type="text" class="form-control"  name="card_number" />
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expiration Month*</label>
                                    <select class="form-control"  name="expiry_month">
                                        <option value="">Choose Month</option>
                                        @for($i = 1; $i<=12; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expiration Year*</label>
                                    <select class="form-control"  name="expiry_year">
                                        <option value="">Choose Year</option>
                                        @for($i = 2020; $i<=2050; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Security Code*</label>
                                    <input type="text" name="security_code" class="form-control" />
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing Zip Code*</label>
                                    <input type="text" name="zip" class="form-control" />
                                </div>  
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing Address*</label>
                                    <input type="text" name="b_address" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing City/State</label>
                                    <input type="text" name="b_city_state" class="form-control" />
                                </div>  
                            </div>
                        </div>
                    </div>  
                        
                    <div class=" btn-rw text-center">   
                             <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>  
                        
                    </div>
                  </div>
                
                    
            </form>
                
            </div>
            
            
        </div>
        
         </section>
      <!-- End Team section-->
@endsection
