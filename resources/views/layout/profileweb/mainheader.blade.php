<section class="gnb_one_wrp">
    <div class="gnb_one_bx">		
        <a class="gnb_logo" href="http://www.webzen.com/" rel=""><span class="none">SLG</span></a>		
        <nav>			
            <ul class="menu">				
                <li><a class="game" href="#" idx="2" rel="">GAMES</a></li>				
                <li><a href="//pay.slg.vn/topcoin" rel="">NẠP COIN</a></li>				
                <li class="support"><a href="http://cs.webzen.com/FAQ/List" rel="">HỖ TRỢ</a></li>				
                <li><a target="_blank" href="http://diendan.slg.vn/forum.php" idx="3" rel="">DIỄN ĐÀN</a></li>			
            </ul>		
        </nav>        
        <article class="user_conect_wrp">		    
            <div class="user_conect">			
                <div class="user_join_before">	
                    @if (isset(Auth::user()->id) && !empty(Auth::user()->id))
                        <a class="login" href="javascript:void(0);" rel="" >Chào, {{ Auth::user()->name }}</a>				
                        <a class="register" href="javascript:void(0);" onclick="slg_register();">Thoát</a>
                    @else
                        <a class="login" href="javascript:void(0);" rel="" onclick="slg_check_loginming();">ĐĂNG NHẬP</a>				
                        <a class="register" href="javascript:void(0);" onclick="slg_register();">ĐĂNG KÝ</a>
                    @endif
                    
                </div>		    
            </div>		    

        </article>
    </div>	
    <div idx="2" class="game_list_wrp">		
        <div style="display: none;" class="game_list_bx" id="game_list_open">		
            <ul class="game_list">
                <?php
                $lisgame = App\Models\MerchantApp::getGameList();
                if(count($lisgame >0)){
                    foreach ($lisgame as $key => $game) {
                ?>
                        <li>			
                            <a rel="" href="{{$game->url}}">		
                                <img src="{{$game->img}}" alt="">				
                                <span class="a2"></span>	
                                <strong>{{$game->name}}</strong>	
                            </a>			
                        </li>
                <?php
                    }
                }
                ?>
            </ul>	
        </div>
    </div> 
    <div idx="3" class="comm_list_wrp">      
        <div style="display: none;" class="comm_list_bx" id="comm_list_open">      
            <ul class="comm_list">    
                <li>            
                    <a rel="" href="http://forum.slg.vn/"><img src="//pay.slg.vn/portal/images/communty_sum01.gif" alt=""><strong>Forums</strong></a>           
                </li>          
                <li>           
                    <a rel="" href="http://www.slg.vn/volunteer-program"><img src="//pay.slg.vn/portal/images/communty_sum02.gif" alt=""><strong>SLG Volunteer<br>Program</strong></a>          
                </li>          
                <li class="redeem">    
                    <a rel="" href="http://www.slg.vn/Coupon"><img src="//pay.slg.vn/portal/images/communty_sum03.gif" alt=""><strong>Redeem<br>Coupon Code</strong></a>             
                </li>        
            </ul>     
        </div>  
    </div>
</section>