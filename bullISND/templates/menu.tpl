<ul id="menuBas">
	<li class="titreMenu">Bulletin</li>
	<li><a href="../index.php"><img src="../images/homeIco.png" title="Retour" alt="home"></a></li>
	<li><a href="index.php"><img src="images/bullISND.png" alt="bullISND" title="Page d'accueil du bulletin"></a></li>
	<li><a href="javascript:void(0)">Imprimer</a>
        <ul>
			<li><a href="index.php?action=ecran&amp;mode=bulletinIndividuel">Bulletins individuels à l'écran</a></li>
			<li><a href="index.php?action=pdf&amp;mode=bulletinIndividuel">Bulletins individuels PDF</a></li>
			<li><a href="index.php?action=pdf&amp;mode=bulletinClasse">Bulletins par classe PDF</a></li>
			<li><a href="index.php?action=pdf&amp;mode=niveau">Bulletins par niveaux</a></li>
			<li><a href="index.php?action=pdf&amp;mode=archive">Archives des bulletins</a></li>
			{if ($userStatus == 'admin')}
				<li><a href="index.php?action=pdf&amp;mode=gestFichiers">Gestion des fichiers PDF</a></li>
			{/if}
		</ul>
	</li>
	{if ($userStatus == 'admin') || ($userStatus == 'direction')}
	<li><a href="javascript:void(0)">Coordinateurs</a>
		<ul>
			<li><a href="index.php?action=nota">Notices coordinateurs</a></li>
			<!-- <li><a href="index.php?action=direction&amp;mode=competences">Rapports de compétences</a></li> -->
			<li><a href="index.php?action=parEcole">Résultats par école</a></li>
			<li><a href="index.php?action=direction&amp;mode=padEleve">Bloc Note Élèves</a></li>
		</ul>
	</li>
	{/if}
	<li><a href="javascript:void(0)">Carnet de cotes</a>
		<ul>
		<li><a href="index.php?action=carnet&amp;mode=gererCotes&amp;tri=alpha">Gérer les cotes (tri alpha)</a></li>
		<li><a href="index.php?action=carnet&amp;mode=gererCotes&amp;tri=classe">Gérer les cotes (tri par classe)</a></li>
		<li><a href="index.php?action=carnet&amp;mode=poidsCompetences">Poids des compétences</a></li>
		<li><a href="index.php?action=carnet&amp;mode=oneClick">One Click Bulletin</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0)">Bulletin</a>
		<ul>
		<li><a href="index.php?action=gestionBaremes&amp;mode=voir">Pondération par période</a></li>
		<li><a href="index.php?action=gestionCotes&amp;mode=voir">Aperçu des cotes pour vos cours</a></li>
		<li><a href="index.php?action=gestEncodageBulletins&amp;tri=alpha">Encodage des bulletins (ordre alpha)</a></li>
		<li><a href="index.php?action=gestEncodageBulletins&amp;tri=classes">Encodage des bulletins (ordre classes)</a></li>
		</ul>
	</li>
	{*
	{if $userStatus == 'educ' || $userStatus == 'admin'}
	<li><a href="javascript:void(0)">Éducateurs</a>
		<ul>
			<li><a href="#">Fiches disciplinaires</a></li>
		</ul>
	</li>
	{/if}
	*}
	{if ($titulaire) }
	<li><a href="javascript:void(0)">Titulaire de {','|implode:$titulaire}</a>
		<ul>
			<li><a href="index.php?action=titu&amp;mode=remarques">Remarques aux bulletins</a></li>
			<li><a href="index.php?action=titu&amp;mode=verrous">(Dé)-verrouiller les bulletins</a></li>
		</ul>
	</li>
	{/if}
	<li><a href="javascript:void(0)">Feuilles de délibés</a>
		<ul>
			<li><a href="index.php?action=delibes&amp;mode=parClasse">Feuille de délibé par classe</a></li>
			<li><a href="index.php?action=delibes&amp;mode=individuel">Feuille de délibé individuelle</a></li>
			<li><a href="index.php?action=delibes&amp;mode=synthese">Synthèses par classe (toutes périodes)</a></li>
			<!--  <li><a href="index.php?action=delibes&amp;mode=diffPremiere">Situations 1e/2e</a></li> -->
		</ul>
		</li>
	{if $userStatus eq 'admin'}
	<li><a href="javascript:void(0)">Admin</a>
		<ul>
		<li><a href="index.php?action=init">Initialisations</a></li>
		<li><a href="index.php?action=admin&amp;mode=remplacants">Profs remplacés</a></li>
		<li><a href="index.php?action=gestCours&amp;mode=matieres">Gestion des matières</a></li>
		<li><a href="index.php?action=gestCours&amp;mode=editCours">Gestion des cours</a></li>
		<li><a href="index.php?action=admin&amp;mode=attributionsProfs">Attrib. cours aux profs</a></li>
		<li><a href="index.php?action=admin&amp;mode=attributionsEleves">Attrib. élèves aux cours</a></li>
		<li><a href="index.php?action=admin&amp;mode=programmeEleve">Attrib. cours aux élèves</a></li>		
		<li><a href="index.php?action=admin&amp;mode=competences">Gestion des compétences</a></li>
		<li><a href="index.php?action=admin&amp;mode=poserVerrous">Ouvrir/fermer des verrous</a></li>
		<li><a href="index.php?action=admin&amp;mode=verrouClasseCoursEleve">Verrous classe/cours/élève</a></li>
		<li><a href="index.php?action=admin&amp;mode=situations">Modifier les situations</a></li>
		<li><a href="index.php?action=admin&amp;mode=alias">Prendre un alias</a></li>
		</ul>
	</li>
	{/if}
</ul>

<script type="text/javascript">
{literal}
// listen for scroll
var positionElementInPage = $('#menuBas').offset().top;

$(window).scroll(
	function() {
		if ($(window).scrollTop() >= positionElementInPage) {
			// fixed
			$('#menuBas').addClass("floatable");
		} else {
			// relative
			$('#menuBas').removeClass("floatable");
		}
	}
);
{/literal}
</script>
