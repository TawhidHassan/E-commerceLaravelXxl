@extends('layouts.frontLayout.front_design')
@section('content')
<section id="slider"><!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-carousel" data-slide-to="1"></li>
                        <li data-target="#slider-carousel" data-slide-to="2"></li>
                    </ol>
                    
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="col-sm-6">
                                <h1><span>E</span>-SHOPPER</h1>
                                <h2>Free E-Commerce Template</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                <button type="button" class="btn btn-default get">Get it now</button>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/girl1.jpg" class="girl img-responsive" alt="" />
                                <img src="images/home/pricing.png"  class="pricing" alt="" />
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-sm-6">
                                <h1><span>E</span>-SHOPPER</h1>
                                <h2>100% Responsive Design</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                <button type="button" class="btn btn-default get">Get it now</button>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/girl2.jpg" class="girl img-responsive" alt="" />
                                <img src="images/home/pricing.png"  class="pricing" alt="" />
                            </div>
                        </div>
                        
                        <div class="item">
                            <div class="col-sm-6">
                                <h1><span>E</span>-SHOPPER</h1>
                                <h2>Free Ecommerce Template</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                <button type="button" class="btn btn-default get">Get it now</button>
                            </div>
                            <div class="col-sm-6">
                                <img src="images/home/girl3.jpg" class="girl img-responsive" alt="" />
                                <img src="images/home/pricing.png" class="pricing" alt="" />
                            </div>
                        </div>
                        
                    </div>
                    
                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</section><!--/slider-->

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="left-sidebar">
                    <h2>Category</h2>
                    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
                        
                        <div class="panel panel-default">
                            @foreach ($categories as $cat)
                            @if ($cat->status==1)
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordian" href="#{{$cat->id}}">
                                        <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                        {{$cat->name}}
                                    </a>
                                </h4>
                            </div>
                            <div id="{{$cat->id}}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <ul>
                                        @foreach ($cat->categories as $subcat)
                                        @if ($subcat->status==1)
                                        <li><a href="{{asset('/products/'.$subcat->url)}}">{{$subcat->name}}</a></li>
                                        @endif
                                       @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                            <hr>
                            <hr>
                        @foreach ($categories as $cat)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            <h4 class="panel-title"><a href="#">{{$cat->name}}</a></h4>
                            </div>
                        </div>
                        @endforeach
                       
                    </div><!--/category-products-->
                
                    {{-- <div class="price-range"><!--price-range-->
                        <h2>Price Range</h2>
                        <div class="well text-center">
                             <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600" data-slider-step="5" data-slider-value="[250,450]" id="sl2" ><br />
                             <b class="pull-left">$ 0</b> <b class="pull-right">$ 600</b>
                        </div>
                    </div><!--/price-range--> --}}
                
                
                </div>
            </div>
            
            <div class="col-sm-9 padding-right">
                <div class="features_items"><!--features_items-->
                    <h2 class="title text-center">All Items</h2>

                    @foreach ($productsAll as $product)
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="{{asset('images/backend_images/products/small/'.$product->image)}}" alt="" />
                                    <h2>Tk.{{$product->price}}</h2>
                                        <p>{{$product->name}}</p>
                                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                    {{-- <div class="product-overlay">
                                        <div class="overlay-content">
                                            <h2>Tk.{{$product->price}}</h2>
                                            <p>{{$product->name}}</p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                    </div> --}}
                            </div>
                            <div class="choose">
                                <ul class="nav nav-pills nav-justified">
                                    <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
                                    <li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                   
                    
                </div><!--features_items-->
            </div>
        </div>
    </div>
</section>  
@endsection