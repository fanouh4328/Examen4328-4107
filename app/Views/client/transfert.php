<form action="<?= base_url('transaction/processTransfert') ?>" method="POST">
    <!-- Input Numéros (séparés par des virgules) -->
    <div class="mb-3">
        <label for="numeros" class="form-label">Numéro(s) du/des destinataire(s)</label>
        <input type="text" name="numeros" id="numeros" class="form-control" placeholder="Ex: 0331234567, 0329876543" required>
        <small class="text-muted">Séparez les numéros par une virgule pour un envoi multiple.</small>
    </div>

    <!-- Input Montant Total -->
    <div class="mb-3">
        <label for="montant_total" class="form-label">Montant Total à partager (Ar)</label>
        <input type="number" name="montant_total" id="montant_total" class="form-control" min="100" required>
    </div>

    <!-- Checkbox Inclure les frais de retrait -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="inclure_frais_retrait" id="inclure_frais_retrait" value="1">
        <label class="form-check-label" for="inclure_frais_retrait">
            <strong>Inclure les frais de retrait</strong> (Le destinataire recevra le montant net exact sans payer de frais au retrait)
        </label>
    </div>

    <button type="submit" class="btn btn-primary w-100">Confirmer le transfert</button>
</form>