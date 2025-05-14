<form id="ajax-search-form" method="GET" action="" class="mb-5">
      <h5 class="mb-4">Filter Options</h5>

      <!-- Price Range -->
      <div class="card mb-3">
        <div class="card-header">Price Range</div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span>$<span id="priceMin">0</span></span>
            <span>$<span id="priceMax">1000</span></span>
          </div>
          <input type="range" class="form-range" min="0" max="1000" id="priceRange">
        </div>
      </div>

      <!-- Rating -->
      <div class="card mb-3">
        <div class="card-header">Minimum Rating</div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <span><span id="ratingValue">0</span> stars</span>
            <span>5 stars</span>
          </div>
          <input type="range" class="form-range" min="0" max="5" step="0.5" id="ratingRange">
        </div>
      </div>

      <!-- For Who -->
      <div class="card mb-3">
        <div class="card-header">For Who</div>
        <div class="card-body">
          <select class="form-select">
            <option value="">Any</option>
            <option>Wife</option>
            <option>Husband</option>
            <option>Girlfriend</option>
            <option>Boyfriend</option>
            <option>Brother</option>
            <option>Sister</option>
            <option>Father</option>
            <option>Mother</option>
            <option>Son</option>
            <option>Daughter</option>
            <option>Friend</option>
            <option>Colleague</option>
          </select>
        </div>
      </div>

      <!-- Age Group -->
      <div class="card mb-3">
        <div class="card-header">Age Group</div>
        <div class="card-body">
          <select class="form-select" multiple size="4">
            <option>Baby (0-2)</option>
            <option>Child (3-12)</option>
            <option>Teen (13-19)</option>
            <option>Young Adult (20-35)</option>
            <option>Adult (36-55)</option>
            <option>Senior (56-75)</option>
            <option>Elder (75+)</option>
          </select>
        </div>
      </div>

      <!-- Participants -->
      <div class="card mb-3">
        <div class="card-header">Participants</div>
        <div class="card-body">
          <input type="range" class="form-range" min="1" max="20" id="participantsRange">
          <div id="participantsValue" class="text-center mt-2">1-4 people</div>
        </div>
      </div>

      <!-- Time Length -->
      <div class="card mb-3">
        <div class="card-header">Duration</div>
        <div class="card-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="30min">
            <label class="form-check-label" for="30min">30 minutes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="1hour">
            <label class="form-check-label" for="1hour">1 hour</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="2hours">
            <label class="form-check-label" for="2hours">2 hours</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="halfday">
            <label class="form-check-label" for="halfday">Half day (4 hours)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="fullday">
            <label class="form-check-label" for="fullday">Full day</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="multipledays">
            <label class="form-check-label" for="multipledays">Multiple days</label>
          </div>
        </div>
      </div>

      <!-- Region -->
      <div class="card mb-3">
        <div class="card-header">Athens Regions</div>
        <div class="card-body">
          <select class="form-select" multiple size="5">
            <option>Acropolis Area</option>
            <option>Plaka</option>
            <option>Monastiraki</option>
            <option>Syntagma</option>
            <option>Kolonaki</option>
            <option>Exarchia</option>
            <option>Omonia</option>
            <option>Psiri</option>
            <option>Gazi</option>
            <option>Kifissia</option>
            <option>Glyfada</option>
            <option>Vouliagmeni</option>
            <option>Piraeus</option>
            <option>Nea Smyrni</option>
          </select>
        </div>
      </div>

      <!-- Keywords -->
      <div class="card mb-3">
        <div class="card-header">Activity Type</div>
        <div class="card-body">
          <select class="form-select" multiple size="5">
            <option>Water Activities</option>
            <option>Sports</option>
            <option>Health & Wellness</option>
            <option>Adventure</option>
            <option>Culinary</option>
            <option>Cultural</option>
            <option>Art & Craft</option>
            <option>Music</option>
            <option>Dance</option>
            <option>Nature</option>
            <option>History</option>
            <option>Photography</option>
          </select>
        </div>
      </div>

      <!-- Occasion -->
      <div class="card mb-3">
        <div class="card-header">Occasion</div>
        <div class="card-body">
          <select class="form-select" multiple size="4" name="occasion[]">
            <option>Birthday</option>
            <option>Anniversary</option>
            <option>Date Night</option>
            <option>Bachelor Party</option>
            <option>Bachelorette Party</option>
            <option>Family Gathering</option>
            <option>Team Building</option>
            <option>Just for Fun</option>
            <option>Honeymoon</option>
            <option>Graduation</option>
          </select>
        </div>
      </div>

      <!-- Accessibility -->
      <div class="card mb-3">
        <div class="card-header">Accessibility</div>
        <div class="card-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="wheelchair">
            <label class="form-check-label" for="wheelchair">Wheelchair Accessible</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="blind">
            <label class="form-check-label" for="blind">Blind-Friendly</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="deaf">
            <label class="form-check-label" for="deaf">Deaf-Friendly</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="senior">
            <label class="form-check-label" for="senior">Senior-Friendly</label>
          </div>
        </div>
      </div>

      <!-- Learning Outcomes -->
      <div class="card mb-3">
        <div class="card-header">Learning Outcomes</div>
        <div class="card-body">
          <select class="form-select" multiple size="3">
            <option>Educational</option>
            <option>Skill-Building</option>
            <option>Creative</option>
            <option>Physical Development</option>
            <option>Emotional Growth</option>
            <option>Social Skills</option>
          </select>
        </div>
      </div>

      <!-- Special Features -->
      <div class="card mb-3">
        <div class="card-header">Special Features</div>
        <div class="card-body">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="unique">
            <label class="form-check-label" for="unique">Unique Experiences</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="luxury">
            <label class="form-check-label" for="luxury">Luxurious Experiences</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="offers">
            <label class="form-check-label" for="offers">Special Offers</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="eco">
            <label class="form-check-label" for="eco">Eco-Friendly</label>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-grid gap-2">
        <button class="btn btn-primary">Apply Filters</button>
        <button class="btn btn-outline-secondary">Reset All</button>
      </div>

    
</form>