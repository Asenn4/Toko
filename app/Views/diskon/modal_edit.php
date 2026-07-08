<?php foreach ($discounts as $item) : ?>
    <!-- Edit Modal Begin -->
    <div class="modal fade" id="editModal-<?= $item['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open(base_url('diskon/edit/' . $item['id'])); ?>
                <?= csrf_field(); ?>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <?= form_label('Tanggal', 'tanggal'); ?>
                        <input type="date" name="tanggal" class="form-control" value="<?= $item['tanggal'] ?>" readonly>
                    </div>
                
                    <div class="mb-3">
                        <?= form_label('Nominal', 'nominal'); ?>
                        <?= form_input([
                            'type'        => 'number',
                            'name'        => 'nominal',
                            'class'       => 'form-control',
                            'placeholder' => 'Nominal Diskon',
                            'value'       => $item['nominal'],
                            'required'    => true
                        ]); ?>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                
                    <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']); ?>
                </div>
                
                <?= form_close(); ?>
            </div>
        </div>
    </div>
    <!-- Edit Modal End -->
<?php endforeach ?>
