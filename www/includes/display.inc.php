<section class="right" id="displayDiv">
    <img id="displayDivCloseButton" title="Fermer" class="icon-lowopacity float-right" src="ressources/icons/close.png" />
    <h3>AFFICHAGE</h3>

    <div class="div-generic-gray">
        <?php
        if (Common::isadmin()) { ?>
            <h5>Informations</h5>
                
            <!-- afficher ou non la taille des repos/sections -->
            <label class="onoff-switch-label">
                <input type="hidden" name="printRepoSize" value="off" />
                <input class="onoff-switch-input" type="checkbox" name="printRepoSize" value="on" <?php if (PRINT_REPO_SIZE == "yes") echo 'checked'; ?> />
                <span class="onoff-switch-slider"></span>
            </label>
            <span> Afficher la taille du repo</span><br>

            <!-- afficher ou non le type des repos (miroir ou local) -->
            <label class="onoff-switch-label">
                <input type="hidden" name="printRepoType" value="off" />
                <input class="onoff-switch-input" type="checkbox" name="printRepoType" value="on" <?php if (PRINT_REPO_TYPE == "yes") echo 'checked'; ?> />
                <span class="onoff-switch-slider"></span>
            </label>
            <span> Afficher le type du repo</span><br>

            <!-- afficher ou non la signature gpg des repos -->
            <label class="onoff-switch-label">
                <input type="hidden" name="printRepoSignature" value="off" />
                <input class="onoff-switch-input" type="checkbox" name="printRepoSignature" value="on" <?php if (PRINT_REPO_SIGNATURE == "yes") echo 'checked'; ?> />
                <span class="onoff-switch-slider"></span>
            </label>
            <span> Afficher la signature du repo</span>
            
            <br>
            <br>

            <h5>Cache</h5>
            <p>Utiliser <b>/dev/shm</b> pour mettre en ram la liste des repos (recommandé)</p>
            <!-- mettre en cache ou non la liste des repos -->
            <label class="onoff-switch-label">
                <input type="hidden" name="cache_repos_list" value="off" />
                <input class="onoff-switch-input" type="checkbox" name="cacheReposList" value="on" <?php if (CACHE_REPOS_LIST == "yes") echo 'checked'; ?> />
                <span class="onoff-switch-slider"></span>
            </label>
            <span> Mettre en cache dans /dev/shm</span><br>

            <br>
            <button id="repos-display-conf-btn" type="submit" class="btn-large-blue">Enregistrer</button>
<?php } ?>
    </div>
</section>