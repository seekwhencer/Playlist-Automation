<?php

            $this->addMeta('http-equiv', 'Content-Type', 'content', 'text/html; charset=UTF-8');
            $this->addMeta('name','robots','content','index,follow');
            //$this->addMeta('name','viewport','content','width=1024');
            $this->addMeta('name','viewport','content','width=device-width, initial-scale=1, maximum-scale=1');
            
            $this->addMeta('property','og:url','content','http://www.wandae.org');
            $this->addMeta('property','og:image','content','http://radio.wandae.org/images/wandae.jpg?'.time());
            $this->addMeta('property','og:title','content',PAGE_NAME);
            $this->addMeta('property','og:description','content',PAGE_CLAIM);
            
