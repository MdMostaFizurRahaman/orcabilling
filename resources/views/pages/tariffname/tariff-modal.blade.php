<!-- Modal -->
<div class="modal fade tariff-modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form @submit.prevent="editMode ? update() : save()">
                <div class="modal-header">
                    <h5 v-show="!editMode" class="modal-title" id="createModalLabel"><i class="ti-marker-alt m-r-10"></i> Create New Tariff</h5>
                    <h5 v-show="editMode" class="modal-title" id="createModalLabel"><i class="ti-marker-alt m-r-10"></i> Edit Tariff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Tariff Name</label>
                        <input v-model="form.name" type="text" name="name" :class="{ 'is-invalid': form.errors.has('name') }" class="form-control" placeholder="Name">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="currency_id">Select Currency</label>
                        <select  v-model="form.currency_id" name="currency_id" class="form-control" :class="{ 'is-invalid': form.errors.has('currency_id') }">
                            <option v-for='currency in currencies' :key='currency.id' :value='currency.id'>@{{currency.name}}</option>
                        </select>
                        <has-error :form="form" field="currency_id"></has-error>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"><i class="ti-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>