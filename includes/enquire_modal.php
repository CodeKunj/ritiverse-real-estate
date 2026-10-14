<?php
// Luxury VIP Enquiry Modal
global $config;
?>
<div id="enquireModal" class="lumera-modal-backdrop" onclick="if(event.target===this) closeEnquireModal()">
  <div class="lumera-modal-box">
    <button type="button" onclick="closeEnquireModal()" class="absolute top-5 right-5 text-white/60 hover:text-white text-2xl leading-none">&times;</button>
    <div class="mb-6">
      <span class="text-xs uppercase tracking-widest text-[#E0D4C6] font-semibold">Private Advisory</span>
      <h3 class="text-2xl font-heading font-medium text-white mt-1">Acquire at RiTiVerse</h3>
      <p class="text-sm text-white/60 mt-1">Complete your request below to receive confidential floorplans, private pricing, and VIP concierge access.</p>
    </div>

    <form id="enquireForm" method="POST" action="enquire_handler.php" onsubmit="submitEnquiry(event)">
      <div class="flex flex-col gap-4">
        <div>
          <label class="block text-xs uppercase tracking-wider text-white/70 mb-1.5 font-medium">Full Name *</label>
          <input type="text" name="name" required placeholder="e.g. Vikramaditya Singhania" class="lumera-input">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs uppercase tracking-wider text-white/70 mb-1.5 font-medium">Email Address *</label>
            <input type="email" name="email" required placeholder="name@domain.com" class="lumera-input">
          </div>
          <div>
            <label class="block text-xs uppercase tracking-wider text-white/70 mb-1.5 font-medium">Phone Number *</label>
            <input type="tel" name="phone" required placeholder="+91 98200 45678" class="lumera-input">
          </div>
        </div>

        <div>
          <label class="block text-xs uppercase tracking-wider text-white/70 mb-1.5 font-medium">Preferred Residence</label>
          <select name="residence" class="lumera-input text-white bg-[#1c1b1a]">
            <option value="general">Select residence of interest...</option>
            <option value="arabian-seafront">The Arabian Seafront Residence (Worli Sea Face, Mumbai)</option>
            <option value="malabar-crest">Malabar Crest Penthouse (Malabar Hill, Mumbai)</option>
            <option value="goa-coastal">Goa Coastal Estate (Assagao, North Goa)</option>
            <option value="camellias-reserve">The Camellias Reserve (Golf Course Rd, Delhi NCR)</option>
          </select>
        </div>

        <div>
          <label class="block text-xs uppercase tracking-wider text-white/70 mb-1.5 font-medium">Confidential Message</label>
          <textarea name="message" rows="3" placeholder="Share your investment horizon, bespoke architectural preferences, or questions..." class="lumera-input resize-none"></textarea>
        </div>

        <button type="submit" id="enquireSubmitBtn" class="mt-2 w-full py-4 rounded-full bg-[#E0D4C6] text-[#1c1b1a] font-medium tracking-wide font-heading hover:bg-white transition-all duration-300">
          Submit Private Request
        </button>
      </div>
    </form>
  </div>
</div>

<div id="lumeraToast" class="lumera-toast">
  <span id="lumeraToastMessage">Thank you. Your enquiry has been received.</span>
</div>
