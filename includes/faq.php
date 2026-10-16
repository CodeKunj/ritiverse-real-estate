<?php
// FAQs Component - Dynamically Managed from RiTiVerse Admin CMS
global $config;
$faqs = $config['faqs'] ?? [];
?>
<section class="py-[7%] bg-[#F8F7F5] text-[#1C1B1A] max-md:py-[15%]" id="faq">
  <div class="w-full ml-auto max-md:w-full max-[1025px]:w-full px-[5%] max-md:px-[6%]">
    <div data-copy-wrapper="true" class="opacity-100">
      <h2 data-para-anim="true" class="font-heading text-[#1C1B1A] max-md:mb-[10vw] font-medium! text-[3.8vw] leading-[1.2] max-[1025px]:text-[6.8vw] max-md:text-[8.8vw] mb-[5vw]">
        FAQs
      </h2>
    </div>

    <div class="w-full">
      <?php foreach ($faqs as $i => $faq): ?>
      <div class="faq-item cursor-pointer group relative mb-[1.1vw] overflow-hidden rounded-md bg-white border border-[#111111]/15 transition-colors duration-500 ease-[cubic-bezier(0.215,0.61,0.355,1)] aria-expanded:border-[#ca8216] max-md:mb-[4vw] max-md:rounded-[4.5vw] max-[1025px]:mb-[2vw] max-[1025px]:rounded-[2.5vw]" 
           role="button" 
           tabindex="0" 
           aria-expanded="false" 
           aria-controls="faq-ans-<?= $i ?>" 
           id="faq-q-<?= $i ?>">
        <div class="absolute inset-0 origin-top scale-y-0 bg-[#E0D4C6] transition-transform duration-500 ease-[cubic-bezier(0.215,0.61,0.355,1)] group-aria-expanded:scale-y-100"></div>
        <div class="flex w-full items-center justify-between gap-6 relative z-2 text-[1.5vw] font-medium text-black max-md:text-[5vw] max-[1025px]:text-[3.5vw] gap-[2vw] font-heading py-[1.6vw] pl-[2.2vw] pr-[1.6vw] text-left max-md:py-[5vw] max-md:pl-[6vw] max-md:pr-[5vw] max-[1025px]:py-[3vw] max-[1025px]:pl-[4vw] max-[1025px]:pr-[3vw] [&>div:first-child]:max-md:w-[80%]">
          <div class="flex-1"><?= htmlspecialchars($faq['q'] ?? '') ?></div>
          <div class="shrink-0 relative flex h-[2.6vw] w-[2.6vw] items-center justify-center rounded-full border border-black text-black max-md:h-[9vw] max-md:w-[9vw] max-[1025px]:h-[6vw] max-[1025px]:w-[6vw]">
            <span class="relative flex h-full w-full items-center justify-center" aria-hidden="true">
              <span class="absolute h-[1.5px] w-[40%] bg-current"></span>
              <span class="absolute h-[1.5px] w-[40%] bg-current transition-transform duration-700 ease-[cubic-bezier(0.65,0,0.35,1)] motion-reduce:transition-none rotate-90"></span>
            </span>
          </div>
        </div>
        <div id="faq-ans-<?= $i ?>" role="region" style="display:none;height:0;overflow:hidden;opacity:0" class="relative z-2">
          <div class="w-[65%] space-y-[1.5vw] pl-[2.2vw] pb-[1.8vw] text-[1.15vw] leading-normal font-normal text-black max-md:w-[80%] max-md:space-y-[4vw] max-md:pl-[6vw] max-md:pb-[6vw] max-md:text-[4vw] max-[1025px]:w-[90%] max-[1025px]:pl-[4vw] max-[1025px]:pb-[3vw] max-[1025px]:text-[2.7vw]">
            <div>
              <p data-para-anim="true"><?= nl2br(htmlspecialchars($faq['a'] ?? '')) ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
