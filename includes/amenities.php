<?php
/**
 * Amenities & Showcase (At the Heart of Dubai Living) Component
 */
global $config;
?>
<section id="showcase" class="py-[10%] bg-[#F8F7F5] text-[#1c1b1a] relative z-5 max-[1025px]:pb-[15%]" data-scroll="true" data-scroll-speed="-0.3">
  <div class="px-[5%] h-full relative">
    <div class="flex w-full gap-[10vw] max-[1025px]:flex-col">
      <div data-copy-wrapper="true" class="opacity-100 w-[50%] max-[1025px]:w-full">
        <h2 data-para-anim="true" class="text-[#1C1B1A] font-heading font-medium! text-[3.8vw] leading-[1.2] max-[1025px]:text-[6.8vw] max-md:text-[8.8vw] max-[1025px]:w-full">
          At the Heart of<br/>Prime India
        </h2>
      </div>
      
      <div class="w-[40vw] h-[22vw] overflow-hidden rounded-full video-block absolute right-[10%] rtl:left-[10%] rtl:right-auto max-[1025px]:w-[90vw] max-[1025px]:h-[50vw] max-[1025px]:static max-[1025px]:rounded-[4vw] bg-[#1c1b1a] shadow-2xl">
        <video 
          id="showcaseVideo"
          src="assets/dubai-living.0q_n0efbm6xc4.mp4" 
          poster="assets/video-poster.0gfrgyivwvqfn.webp" 
          autoplay 
          muted 
          loop 
          playsinline 
          preload="auto" 
          class="w-full h-full object-cover object-center pointer-events-none">
        </video>
        <div class="inner-detail absolute bottom-[8%] left-1/2 -translate-x-1/2 z-6 flex items-center gap-[2vw] rounded-full bg-black/40 backdrop-blur-[12px] px-[2.2vw] py-[1.2vw] opacity-0 max-[1025px]:opacity-100 max-[1025px]:static max-[1025px]:mt-[4vw] max-[1025px]:w-fit max-[1025px]:flex-wrap max-[1025px]:gap-[3vw] max-[1025px]:px-[5vw] max-[1025px]:py-[4vw] border border-white/10 pointer-events-none transition-opacity duration-500">
          <p data-para-anim="true" class="text-[1.1vw] leading-tight text-white max-[1025px]:text-[3vw]">
            Nearby<br/>Landmarks:
          </p>
          <div class="flex items-center gap-[2vw] max-[1025px]:flex-wrap max-[1025px]:gap-[3vw]">
            <p data-para-anim="true" class="whitespace-nowrap text-[1.1vw] text-white max-[1025px]:text-[3vw]">Bandra-Worli Sea Link</p>
            <span class="h-[1.4vw] w-px shrink-0 bg-white/40 max-[1025px]:hidden"></span>
            <p data-para-anim="true" class="whitespace-nowrap text-[1.1vw] text-white max-[1025px]:text-[3vw]">BKC Financial Hub</p>
            <span class="h-[1.4vw] w-px shrink-0 bg-white/40 max-[1025px]:hidden"></span>
            <p data-para-anim="true" class="whitespace-nowrap text-[1.1vw] text-white max-[1025px]:text-[3vw]">Marine Drive</p>
            <span class="h-[1.4vw] w-px shrink-0 bg-white/40 max-[1025px]:hidden"></span>
            <p data-para-anim="true" class="whitespace-nowrap text-[1.1vw] text-white max-[1025px]:text-[3vw]">Mumbai Airport (BOM)</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="w-full h-full mt-[48vw] flex justify-end max-[1025px]:mt-[7vw]">
      <div data-copy-wrapper="true" class="opacity-100 w-[40%] max-[1025px]:w-full">
        <p data-para-anim="true" class="max-md:text-[4.2vw] max-[1025px]:text-[3vw] mr-[5vw] text-[1.2vw] text-black tracking-wide">
          Perfectly positioned along India's most iconic coastal and urban corridors, RiTiVerse offers unmatched access to premier cultural landmarks, financial districts, and elite social clubs.
        </p>
      </div>
    </div>
  </div>
</section>
