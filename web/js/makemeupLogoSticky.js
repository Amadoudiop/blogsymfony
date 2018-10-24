/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
            var scroll_position = 0;
            var ticking = false;
            document.getElementById("logo_sidebar_sticky").style.opacity = 0;

            function transitionSticky(scroll_position) {
                if (scroll_position <= 300){
                document.getElementById("logo_sidebar_sticky").style.opacity = ((scroll_position)/300);  
                document.getElementById("logo_center_top").style.opacity = (1-((scroll_position)/300));  
                }
                if (scroll_position >= 301){
                    document.getElementById("logo_sidebar_sticky").style.opacity = 1;  
                    document.getElementById("logo_center_top").style.opacity = 0; 
                    }
            }

            window.addEventListener('scroll', function(e) {
                scroll_position = window.scrollY;
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        transitionSticky(scroll_position);
                        ticking = false;
                    });
                }
                ticking = true;
            });
