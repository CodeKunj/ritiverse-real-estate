<?php
// Features & Signature Residences Component
global $config;
$residences = $config['residences'] ?? [];
?>
<section id="lumera-features" class="relative h-[850vh] bg-[#F3F3F3] text-[#1c1b1a] max-[1025px]:h-auto">

<div class="sticky top-0 h-screen overflow-hidden max-[1025px]:static max-[1025px]:h-auto">

<div class="absolute top-[3vw] left-0 right-0 z-40 px-[5vw] flex items-center justify-between pointer-events-none max-[1025px]:hidden">
  <div class="pointer-events-auto flex items-center gap-2 bg-[#1c1b1a]/80 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 shadow-lg">
    <button type="button" onclick="goToResidence(0)" class="residence-tab-btn px-4 py-1.5 rounded-full text-xs font-medium uppercase tracking-wider bg-white text-[#1c1b1a] hover:bg-white/90 transition-all">Overview</button>
    <?php foreach ($residences as $idx => $res): 
      $num = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
    ?>
      <button type="button" onclick="goToResidence(<?= $idx + 1 ?>)" class="residence-tab-btn px-4 py-1.5 rounded-full text-xs font-medium uppercase tracking-wider text-white/70 hover:text-white hover:bg-white/10 transition-all">
        <?= $num ?> <?= htmlspecialchars($res['title']) ?>
      </button>
    <?php endforeach; ?>
  </div>
  <div class="pointer-events-auto flex items-center gap-3">
    <button type="button" onclick="prevResidence()" aria-label="Previous residence" class="flex h-12 w-12 items-center justify-center rounded-full bg-[#1c1b1a] text-white hover:bg-[#ca8216] transition-colors shadow-lg">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <button type="button" onclick="nextResidence()" aria-label="Next residence" class="flex h-12 w-12 items-center justify-center rounded-full bg-[#1c1b1a] text-white hover:bg-[#ca8216] transition-colors shadow-lg">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    </button>
  </div>
</div>

<div class="lumera-residence-track flex h-full w-fit gap-0 max-[1025px]:flex-col max-[1025px]:gap-[12vw] max-[1025px]:pb-[15%]" style="--card-gap:4vw">
  
  <!-- Overview Card -->
  <div id="lumera-residences-_R_8tavb_-textfill" class="flex h-screen w-screen flex-none items-center justify-center overflow-hidden bg-white px-[5vw] max-[1025px]:h-auto max-[1025px]:w-full max-[1025px]:py-[20vh]">
    <div class="split__wrapper relative z-10 mx-auto w-[80%] max-[1025px]:w-[90%]">
      <h2 class="text-center text-[3.8vw] font-heading leading-[1.3] font-medium! text-[#1c1b1a] opacity-100 max-[1025px]:opacity-100 max-[1025px]:text-[5vw] max-md:text-[8.5vw] max-[1025px]:leading-[1.08]">
        Life above the City, Where Every Moment is Framed by Sweeping Skyline Views and Natural Light.
      </h2>
    </div>
  </div>

  <!-- Dynamic Residence Cards -->
  <?php foreach ($residences as $idx => $res): 
    $num = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
    $bedrooms = $res['specs']['bedrooms'] ?? '5 Bedrooms';
    $bathrooms = $res['specs']['bathrooms'] ?? '6 Bathrooms';
    $area = $res['specs']['area'] ?? '11,400 sq. ft.';
    $price = $res['price'] ?? '₹ 48,00,00,000';
    $gallery = $res['gallery'] ?? [];
  ?>
    <article class="lumera-residence-card max-[1025px]:px-[6%] flex h-screen w-[98vw] flex-none overflow-hidden rounded-none bg-[#F3F3F3] max-[1025px]:h-auto max-[1025px]:w-full max-[1025px]:flex-col">
      <div class="h-full w-[42%] overflow-hidden max-md:h-[60vh] max-[1025px]:h-[60vh] max-[1025px]:w-full max-[1025px]:rounded-[4vw]">
        <img alt="<?= htmlspecialchars($res['title']) ?>" loading="lazy" width="1000" height="1000" decoding="async" class="lumera-residence-image h-full w-full object-cover opacity-100 scale-100 max-[1025px]:translate-x-0 max-[1025px]:scale-100 max-[1025px]:opacity-100" style="color:transparent" src="<?= htmlspecialchars($res['primary_image']) ?>"/>
      </div>
      <div class="flex flex-col justify-between px-[3.5vw] py-[3.5vw] max-[1025px]:w-full max-[1025px]:px-0 max-[1025px]:pt-[7vw]">
        <div>
          <p class="lumera-residence-number text-[#1c1b1a] font-heading text-[5.8vw] font-medium! leading-none max-[1025px]:text-[7vw] opacity-100 max-md:text-[12vw] max-[1025px]:opacity-100"><?= $num ?></p>
          <h3 class="lumera-residence-title mt-[2vw] max-[1025px]:text-[5vw] font-heading text-[3.8vw] font-medium! leading-[1.2] text-[#1c1b1a] tracking-tighter opacity-100 max-[1025px]:mt-[5vw] max-[1025px]:max-w-full max-md:text-[7vw] max-[1025px]:opacity-100"><?= htmlspecialchars($res['title']) ?></h3>
          <p class="lumera-residence-copy mt-[3vw] max-w-[38vw] text-[1.25vw] leading-[1.6] text-[#1c1b1a] opacity-100 max-[1025px]:mt-[6vw] max-[1025px]:max-w-full max-[1025px]:text-[3vw] max-md:text-[4vw] max-[1025px]:opacity-100"><?= htmlspecialchars($res['description']) ?></p>
          
          <div class="mt-[2.2vw] grid grid-cols-4 max-[1025px]:mt-[7vw] max-[1025px]:grid-cols-2">
            <div class="lumera-residence-stat w-[14vw] max-[1025px]:w-[45vw] relative py-[0.95vw] opacity-100 max-[1025px]:py-[4vw] max-[1025px]:opacity-100 pr-[2vw] max-[1025px]:px-0">
              <p class="text-[1.45vw] font-medium! leading-none tracking-[-0.04em] max-[1025px]:text-[3.5vw] max-md:text-[5vw]"><?= htmlspecialchars($area) ?></p>
              <p class="mt-[0.8vw] text-[1.25vw] text-black max-[1025px]:mt-[1.8vw] max-[1025px]:text-[2.5vw] max-md:text-[3.2vw]">Living Area</p>
            </div>
            <div class="lumera-residence-stat w-[14vw] max-[1025px]:w-[45vw] relative py-[0.95vw] opacity-100 max-[1025px]:py-[4vw] max-[1025px]:opacity-100 pl-[2vw] pr-[1.2vw] max-[1025px]:px-0">
              <span aria-hidden="true" class="absolute left-0 top-1/2 h-[6vw] w-px -translate-y-1/2 bg-[#DADADA] max-[1025px]:hidden"></span>
              <p class="text-[1.45vw] font-medium! leading-none tracking-[-0.04em] max-[1025px]:text-[3.5vw] max-md:text-[5vw]"><?= htmlspecialchars(preg_replace('/[^0-9]/', '', $bedrooms) ?: $bedrooms) ?></p>
              <p class="mt-[0.8vw] text-[1.25vw] text-black max-[1025px]:mt-[1.8vw] max-[1025px]:text-[2.5vw] max-md:text-[3.2vw]">Bedrooms</p>
            </div>
            <div class="lumera-residence-stat w-[14vw] max-[1025px]:w-[45vw] relative py-[0.95vw] opacity-100 max-[1025px]:py-[4vw] max-[1025px]:opacity-100 pl-[2vw] pr-[1.2vw] max-[1025px]:px-0">
              <span aria-hidden="true" class="absolute left-0 top-1/2 h-[6vw] w-px -translate-y-1/2 bg-[#DADADA] max-[1025px]:hidden"></span>
              <p class="text-[1.45vw] font-medium! leading-none tracking-[-0.04em] max-[1025px]:text-[3.5vw] max-md:text-[5vw]"><?= htmlspecialchars(preg_replace('/[^0-9]/', '', $bathrooms) ?: $bathrooms) ?></p>
              <p class="mt-[0.8vw] text-[1.25vw] text-black max-[1025px]:mt-[1.8vw] max-[1025px]:text-[2.5vw] max-md:text-[3.2vw]">Bathrooms</p>
            </div>
            <div class="lumera-residence-stat w-[14vw] max-[1025px]:w-[45vw] relative py-[0.95vw] opacity-100 max-[1025px]:py-[4vw] max-[1025px]:opacity-100 pl-[2vw] pr-[1.2vw] pr-0 max-[1025px]:px-0">
              <span aria-hidden="true" class="absolute left-0 top-1/2 h-[6vw] w-px -translate-y-1/2 bg-[#DADADA] max-[1025px]:hidden"></span>
              <p class="text-[1.45vw] font-medium! leading-none tracking-[-0.04em] max-[1025px]:text-[3.5vw] max-md:text-[5vw]"><?= htmlspecialchars($price) ?></p>
              <p class="mt-[0.8vw] text-[1.25vw] text-black max-[1025px]:mt-[1.8vw] max-[1025px]:text-[2.5vw] max-md:text-[3.2vw]">Price</p>
            </div>
          </div>
        </div>
        
        <div class="mt-[1vw] flex flex-col items-start justify-between gap-[2vw] max-md:flex-wrap max-[1025px]:mt-[8vw] max-[1025px]:flex-col max-[1025px]:items-start">
          <?php if (!empty($gallery)): ?>
            <div class="flex max-md:flex-gap gap-[1vw] max-[1025px]:gap-[3vw]">
              <?php foreach (array_slice($gallery, 0, 4) as $gIdx => $gThumb): ?>
                <div class="overflow-hidden h-[6.2vw] w-[6.2vw] rounded-md bg-[#ddd4ca] max-[1025px]:rounded-[4vw] group max-[1025px]:h-[20vw] max-[1025px]:w-[20vw]">
                  <img alt="<?= htmlspecialchars($res['title']) ?> preview <?= $gIdx + 1 ?>" loading="lazy" width="1000" height="1000" decoding="async" class="object-cover group-hover:scale-100 duration-300 ease-in-out transition-all h-full w-full" style="color:transparent" src="<?= htmlspecialchars($gThumb) ?>"/>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <a class="inline-flex items-center gap-2 no-underline transition-colors duration-500 ease-[cubic-bezier(0.625,0.05,0,1)] lumera-residence-cta gap-[1vw] pb-[0.6vw] text-[#1c1b1a] opacity-100 transition-opacity max-[1025px]:pb-[1.8vw] max-[1025px]:pt-[4vw] max-[1025px]:opacity-100" href="contact.php?residence=<?= urlencode($res['id']) ?>">
            <div class="mt-[0.3vw]">
              <span class="inline-block relative w-fit">
                <span data-text="Inquire Residence" class="overflow-hidden relative inline-block leading-[1.2] max-[1025px]:text-[3vw] font-heading text-[1.2vw] leading-none max-[1025px]:text-[6vw]">Inquire Residence</span>
                <span aria-hidden="true" class="absolute left-0 w-full bg-current" style="bottom:15%;height:1px;transform:scaleX(0);transform-origin:left center;transition:transform 0.5s cubic-bezier(0.625, 0.05, 0, 1)"></span>
              </span>
            </div>
          </a>
        </div>
      </div>
    </article>
  <?php endforeach; ?>

</div>
</div>
</section>
