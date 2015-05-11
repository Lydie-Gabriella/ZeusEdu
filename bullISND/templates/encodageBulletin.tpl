<div class="container">

	{assign var="ancre" value=$matricule}
	<h2 title="cours {$intituleCours.coursGrp}">Bulletin {$bulletin} - {$intituleCours.statut} {$intituleCours.annee}
		{$intituleCours.libelle} {$intituleCours.nbheures}h -> {$listeClasses|@implode:', '} 
		[{if $intituleCours.nomCours} {$intituleCours.nomCours} {/if}]
	</h2>

{if isset($tableErreurs) && ($tableErreurs != Null)}
	{assign var=ancre value=''}
	{include file="erreurEncodage.tpl"}
{/if}
	
<form name="encodage" id="encodage" action="index.php" method="POST" autocomplete="off" role="form" class="form-vertical">
	<button class="btn btn-primary pull-right noprint enregistrer" type="submit" id="enregistrer">Enregistrer tout</button>
	<button class="btn btn-default pull-right noprint" type="reset" id="annuler">Annuler</button>
	
	<input type="hidden" name="action" value="gestEncodageBulletins">
	<input type="hidden" name="mode" value="enregistrer">
	<input type="hidden" name="bulletin" value="{$bulletin}">
	<input type="hidden" name="coursGrp" value="{$coursGrp}">
	<input type="hidden" name="matricule" id="matricule" value="{$matricule}">
	<input type="hidden" name="tri" value="{$tri}">

	<p class="btn btn-primary noprint" id="ouvrirTout">Déplier ou replier les remarques et situations</p>	
	
	{assign var="tabIndexForm" value="1" scope="global"}
	{assign var="tabIndexCert" value="500" scope="global"}
	{assign var="tabIndexAutres" value="1000" scope="global"}
	
	<select name="selectEleve" id="selectEleve">
		<option value=''>Sélectionner un élève</option>
		{foreach from=$listeEleves key=matricule item=unEleve}
		<option value="{$matricule}" id="{$matricule}" class="select">{$unEleve.nom} {$unEleve.prenom}</option>
		{/foreach}
	</select>
	
	{foreach from=$listeEleves key=matricule item=unEleve}
		
		{assign var=blocage value=$listeVerrous.$matricule.$coursGrp|default:2 scope="global"}
		
		<div class="row">
			
			<div class="col-md-2 col-xs-12 blocGaucheBulletin">
				{include file="photoEleve.inc.tpl"}
			</div>
			
			<div class="col-md-10 col-xs-12">
				{include file="introCotes.inc.tpl"}
			</div>
			
		</div>  <!-- row -->
	
		{*  --------------- commentaire du prof et attitudes (éventuellement) ------------------- *}
		<div class="row">
			{if $listeAttitudes}
				<div class="col-md-7 col-sm-12">
					{include file="blocCommentaireProf.tpl"}
				</div>
				<div class="col-md-5 col-sm-12">
					{include file="blocAttitudes.tpl"}
				</div>
			{else}
				<div class="col-md-12 col-sm-12">
					{include file="blocCommentaireProf.tpl"}
				</div>
			{/if}
		</div>  <!-- row -->
		{*  --------------- commentaire du prof et attitudes (éventuellement) ------------------- *}	
		
			
		{include file="tableSituations.inc.tpl"}
	
		{include file="archiveSitRem.inc.tpl"}
	
		<div class="clearfix" style="border-bottom:1px solid black; padding-bottom:2em;"></div>
		
		{assign var="elevePrecedent_ID" value=$matricule}
	{/foreach}
	
</form>

</div>  <!-- container -->



<script type="text/javascript">

var show = "Cliquer pour voir";
var hide = "Cliquer pour cacher";
var showAll = "Déplier Remarques et Situations";
var hideAll = "Replier Remarques et Situations";
var hiddenAll = true;
var A = "Acquis";
var NA = "Non Acquis";
var report = "Report de cette cote maximale<br>à tous les élèves";
var modifie = false;
var desactive = "Désactivé: modification en cours. Enregistrez ou Annulez.";
var confirmationCopie = "Voulez-vous vraiment recopier ce maximum pour les autres élèves du même cours?\nAttention, les valeurs existantes seront écrasées.";
var confirmationReset = "Êtes-vous sûr(e) de vouloir annuler?\nToutes les informations modifiées depuis le dernier enregistrement seront perdues.\nCliquez sur 'OK' si vous êtes sûr(e).";
var noAccess = "Attention|Les cotes et mentions de ce bulletin ne sont plus modifiables.<br>Contactez l'administrateur ou le/la titulaire.";
var confirmationBeforeUnload = "Vous allez perdre toutes les modifications. Annulez pour rester sur la page.";
var refraichir = "Enregistrez pour rafraîchir le calcul";
var coteArbitraire = "Attention! Vous allez attribuer une cote arbitraire.\nCette fonction ne doit être utilisée que pour des circonstances exceptionnelles.\nVeuillez confirmer.";
var toutesAttitudes = "Cliquez pour attribuer en groupe"

$(document).ready(function(){

	$("input").tabEnter();

	$().UItoTop({ easingType: 'easeOutQuart' });

	$(".ouvrir").prepend("[+]").next().hide();
	$(".ouvrir").css("cursor","pointer").attr("title",show);
	$("#ouvrirTout").css("cursor","pointer");

	$(".radioAcquis").each(function (numero){
		if ($(this).val() == "N" && $(this).attr("checked"))
			$(this).parent().addClass('NA');
	})

	$(".report").attr("title",report).css("cursor","pointer");

	$(".report").click(function(){
		var max = $(this).parent().prev().val();
		var prevClass = $(this).parent().prev().attr("data-id");
		var listeInputs = $('[data-id="'+prevClass+'"]')
		if (confirm(confirmationCopie)) {
			listeInputs.val(max);
			modification();
			}
		});

	// le copier/coller provoque aussi  une "modification"
	$("input, textarea").bind('paste', function(){
		modification()
	});

	$("#ouvrirTout").click(function(){
		if (hiddenAll == true) {
			$(".collapse").collapse('show');
			hiddenAll = false;
			}
			else {
				$(".collapse").collapse('hide');
				hiddenAll = true;
			}
	})

	function modification () {
		if (!(modifie)) {
			modifie = true;
			$(".enregistrer, #annuler").show();
			$("#bulletin").attr("disabled","disabled").attr("title",desactive);
			$("#coursGrp").attr("disabled","disabled").attr("title",desactive);
			$("#envoi").hide();
			$(".totaux input").css("color","white");
			window.onbeforeunload = function(){
				return confirm (confirmationBeforeUnload);
			};
			}
		}

	// bug dans Firefox/Android: l'événement keyup ne suffit pas; la valeur de key renvoyée est aberrante
	// l'événement est donc marqué comme un changement pour n'importe quelle touche... :o()
	$("input, textarea").bind('input keyup change', function(e){
		var readonly = $(this).attr("readonly");
		if (!(readonly)) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		modification();
		
		//if ((key > 31) || (key == 8)) {
		//	modification();
		//	}
		}
	})

	
	// calculs automatiques des totaux
	
	$(".coteTJ").blur(function(e){
		var listeCotes = $(this).closest('table').find('.coteTJ');
		var somme = 0;
		$.each(listeCotes, function(index,note){
			if (!(isNaN(note.value)) && (note.value != '')) {
				laCote = note.value.replace(',','.');				
				somme += parseFloat(laCote);
				}
			})
		if (somme == 0)
			somme = '';
		$(this).closest('table').find('.totalForm').text(somme);
		})

	$(".maxTJ").blur(function(e){
		var listeCotes = $(this).closest('table').find('.maxTJ');
		var somme = 0;
		$.each(listeCotes, function(index,note){
			if (!(isNaN(note.value)) && (note.value != '')) {
				laCote = note.value.replace(',','.');				
				somme += parseFloat(laCote);
				}
			})
		if (somme == 0)
			somme = '';
		$(this).closest('table').find('.totalMaxForm').text(somme);
		})
	
	$(".coteCert").blur(function(e){
		var listeCotes = $(this).closest('table').find('.coteCert');
		var somme = 0;
		$.each(listeCotes, function(index,note){
			if (!(isNaN(note.value)) && (note.value != '')) {
				laCote = note.value.replace(',','.');				
				somme += parseFloat(laCote);
				}
			})
		if (somme == 0)
			somme = '';
		$(this).closest('table').find('.totalCert').text(somme);
		})

	$(".maxCert").blur(function(e){
		var listeCotes = $(this).closest('table').find('.maxCert');
		var somme = 0;
		$.each(listeCotes, function(index,note){
			if (!(isNaN(note.value)) && (note.value != '')) {
				laCote = note.value.replace(',','.');				
				somme += parseFloat(laCote);
				}
			})
		if (somme == 0)
			somme = '';
		$(this).closest('table').find('.totalMaxCert').text(somme);
		})


	$(".radioAcquis").click(function(){
		if ($(this).val()=="N")
			$(this).parent().siblings('.att').andSelf().addClass('NA');
			else $(this).parent().siblings('.att').andSelf().removeClass('NA')
		modification();
		})

	$(".enregistrer, #annuler").hide();

	$("#annuler").click(function(){
		if (confirm(confirmationReset)) {
			this.form.reset();
			$(".acquis").each(function(numero){
				var checked = $(this).next().attr("checked");
				if (checked)
					$(this).parent().removeClass("echecEncodage");
					else $(this).parent().addClass("echecEncodage")
				});
			$("#bulletin").attr("disabled", false);
			$("#coursGrp").attr("disabled", false);
			var totaux = $(".totaux span");
			$.each(totaux,function(){
				$(this).text($(this).data('value'));
				})
			modifie = false;
			$(".enregistrer, #annuler").hide();
			window.onbeforeunload = function(){};
			return false
		}
		})

	$(".enregistrer").click(function(){
		$(this).val("Un moment").addClass("patienter");
		$.blockUI();
		$("#wait").show();
		var ancre = $(this).attr("id");
		$("#matricule").val(ancre);
		window.onbeforeunload = function(){};
	})

	$(".hook, .nohook").click(function(){
		modification();
		var cote = $(this).val().replace(/[\[\]²%\* ]+/g,'');
		// Retrouver le matricule dans Ex: "btnHook-eleve_5042"
		var matricule = $(this).attr("name").split('-')[1].split("_")[1];

		// cacher le champ Input (éventuellement utilisé par la baguette magique)
		$("#situation-eleve_"+matricule).hide();
		// attribution d'une valeur affichée
		if ($(this).hasClass('hook'))
			$("#situationFinale_"+matricule).html('['+cote+']%').show();
			else $("#situationFinale_"+matricule).html(cote+'%').show();
		// attribution d'une valeur au champ input situation-eleve pour$_POST
		$("#situation-eleve_"+matricule).val(cote);
		// indicateur d'attribut de la situation de délibé
		if ($(this).hasClass('hook'))
			$("#attribut-eleve_"+matricule).val('hook');
			else $("#attribut-eleve_"+matricule).val('');
		})

	$(".star").click(function(){
		modification();
		var cote = $(this).val().replace(/[\[\]²%\* ]+/g,'');
		var matricule = $(this).attr("name").split('-')[1].split("_")[1];

		// cacher le champ Input (pas de baguette magique)
		$("#situation-eleve_"+matricule).hide();
		// attribution d'une valeur affichée et affichage du texte
		$("#situationFinale_"+matricule).html(cote+'*%').show();

		// attribution d'une valeur au champ input situation-eleve
		$("#situation-eleve_"+matricule).val(cote);
		// indicateur d'attribut de la situation de délibé
		$("#attribut-eleve_"+matricule).val('star');
	})

	$(".degre").click(function(){
		modification();
		var cote = $(this).val().replace(/[\[\]²%\* ]+/g,'');
		var matricule = $(this).attr("name").split('-')[1].split("_")[1];

		// cacher le champ Input (pas de baguette magique)
		$("#situation-eleve_"+matricule).hide();
		// attribution d'une valeur affichée
		$("#situationFinale_"+matricule).html(cote+'²%').show();

		// attribution d'une valeur au champ input situation-eleve
		$("#situation-eleve_"+matricule).val(cote);
		$("#attribut-eleve_"+matricule).val('degre');
	})


	$(".magic").click(function(){
		if (confirm(coteArbitraire)) {
			modification();
			// var cote = $(this).val().replace(/[\[\]²%\* ]+/g,'');
			var matricule = $(this).attr("name").split('-')[1].split("_")[1];

			// montrer le champ input
			$("#situation-eleve_"+matricule).css('display','block');
			// attribution d'une valeur affichée et affichage du texte
			$("#situationFinale_"+matricule).hide();

			$("#attribut-eleve_"+matricule).val('magique');
		}
	})

	$(".balayette").click(function(){
		modification();
		var matricule = parseInt($(this).attr("id").substr(4,10));
		$("#situationFinale_"+matricule).text('').hide();
		$("#attribut-eleve_"+matricule).val('');
		$("#situation-eleve_"+matricule).val('');
		$(this).fadeOut();
		})


	function goToByScroll(matricule){
     	$('html,body').animate({
			scrollTop: $("#"+matricule).offset().top-100
			},
			'slow'
			);
		}

	$("#selectEleve").change(function(){
		var matricule = $(this).val();
		goToByScroll("el"+matricule);
		})


	$(".clickNE").click(function(){
		$(this).parent().parent().nextAll().find('.nonEvalue').next('input').trigger('click')
		})
	$(".clickNA").click(function(){
		var toto = $(this);
		$(this).parent().parent().nextAll().find('.nonAcquis').next('input').trigger('click');
		
		})
	$(".clickA").click(function(){
		$(this).parent().parent().nextAll().find('.acquis').next('input').trigger('click')
		})


	{if (isset($ancre) && $ancre != '')}
		goToByScroll("el{$ancre}")
	{/if}

});

</script>
