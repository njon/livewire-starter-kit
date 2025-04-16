<form id="ajax-search-form" method="GET" class="mb-5">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               name="min_price" 
                               class="form-control" 
                               placeholder="Min price" 
                               value="{{ request('min_price') }}"
                               min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               name="max_price" 
                               class="form-control" 
                               placeholder="Max price" 
                               value="{{ request('max_price') }}"
                               min="0">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="number" 
                               name="page" 
                               class="form-control" 
                               value="1"
                               min="0">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="age_groups[]" value="Kids" id="age-group-kids" 
                               {{ in_array('Kids', (array) request('age_groups')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="age-group-kids">Kids</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="age_groups[]" value="Teens" id="age-group-teens" 
                               {{ in_array('Teens', (array) request('age_groups')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="age-group-teens">Teens</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="age_groups[]" value="Adults" id="age-group-adults" 
                               {{ in_array('Adults', (array) request('age_groups')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="age-group-adults">Adults</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="age_groups[]" value="Eldery" id="age-group-eldery" 
                               {{ in_array('Eldery', (array) request('age_groups')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="age-group-eldery">Eldery</label>
                    </div>
                </div>



                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
                </div>
            </div>
        </div>
    </div>
</form>