<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport PFE – SmileCare</title>
<style>
/* ===== RESET & BASE ===== */
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11pt;
    line-height: 1.55;
    color: #000000;
}

/* ===== LAYOUT ===== */
.page { padding: 1.6cm 2.5cm 1.8cm 2.5cm; }

/* ===== CHAPTER HEADER (top-right) ===== */
.chap-hdr {
    text-align: right;
    font-size: 9pt;
    color: #555;
    border-bottom: 1px solid #999;
    padding-bottom: 4px;
    margin-bottom: 1.1cm;
}

/* ===== CHAPTER TITLE ===== */
.chapter-title {
    font-size: 34pt;
    font-weight: bold;
    text-align: center;
    margin: 1.4cm 0 1.1cm;
    line-height: 1.2;
}

/* ===== HEADINGS ===== */
h2 { font-size: 13pt; font-weight: bold; margin: 0.7cm 0 0.25cm; }
h3 { font-size: 12pt; font-weight: bold; margin: 0.45cm 0 0.2cm; }
h4 { font-size: 11pt; font-weight: bold; margin: 0.35cm 0 0.15cm; }
.hd-intro { font-size: 12pt; font-weight: bold; margin: 0.4cm 0 0.2cm; }

/* ===== BODY TEXT ===== */
p { text-align: justify; margin-bottom: 0.28cm; }

/* ===== LISTS ===== */
ul, ol { margin: 0.1cm 0 0.28cm 1.2cm; }
li { margin-bottom: 0.16cm; text-align: justify; }
li b { font-weight: bold; }

/* ===== PAGE BREAK ===== */
.pb { page-break-after: always; }

/* ===== TABLE CAPTION ===== */
.tbl-cap {
    text-align: center;
    font-weight: bold;
    font-size: 10pt;
    margin: 0.35cm 0 0.12cm;
}

/* ===== BLUE TABLE ===== */
table.bt {
    width: 100%;
    border-collapse: collapse;
    margin: 0.12cm 0 0.45cm;
    font-size: 10pt;
}
table.bt th {
    background: #1E40AF;
    color: #ffffff;
    padding: 7px 9px;
    font-weight: bold;
    text-align: center;
    border: 1px solid #1E40AF;
}
table.bt td {
    border: 1px solid #BFDBFE;
    padding: 6px 9px;
    vertical-align: top;
}
table.bt tr:nth-child(odd)  td { background: #EFF6FF; }
table.bt tr:nth-child(even) td { background: #DBEAFE; }

/* Blue label cell */
td.bc {
    background: #2563EB !important;
    color: #ffffff;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
    width: 18%;
}

/* ===== FIGURE PLACEHOLDER ===== */
.fig-box {
    border: 1px solid #CBD5E1;
    background: #F8FAFC;
    padding: 28px 20px;
    text-align: center;
    font-style: italic;
    color: #64748B;
    font-size: 10pt;
    margin: 0.28cm 0;
}
.fig-cap {
    text-align: center;
    font-weight: bold;
    font-size: 10pt;
    margin: 0.1cm 0 0.45cm;
}
</style>
</head>
<body>

<!-- ================================================================
     INTRODUCTION GÉNÉRALE
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Introduction générale</div>

    <h1 class="chapter-title">Introduction générale</h1>

    <p>
        Dans le contexte actuel où la transition numérique transforme profondément les pratiques professionnelles,
        le secteur de la santé dentaire se trouve face à un impératif de modernisation de ses outils de gestion.
        Les cabinets dentaires, qui accueillent quotidiennement un flux important de patients, doivent gérer
        simultanément la planification des rendez-vous, le suivi des dossiers médicaux, la facturation, la
        rédaction des ordonnances et la gestion des stocks de matériel médical. Cette multiplicité de tâches,
        souvent traitée de manière manuelle ou avec des outils disparates, constitue une source de pertes de
        temps, d'erreurs et d'inefficacités nuisant à la qualité des soins.
    </p>
    <p>
        Notre projet de fin d'études s'inscrit dans cette démarche de modernisation. Nous avons eu l'opportunité
        de concevoir et de développer <b>SmileCare</b>, une application web complète dédiée à la gestion intégrée
        des cabinets dentaires. Cette plateforme centralise l'ensemble des processus administratifs et médicaux
        d'un cabinet, offrant ainsi aux praticiens et à leur personnel un outil unique, ergonomique et performant.
    </p>
    <p>
        La structure de ce rapport est organisée en six chapitres principaux. Le premier chapitre présente le
        cadre général du projet, comprenant l'organisme d'accueil, la problématique identifiée et la solution
        proposée, ainsi que la méthodologie de développement retenue. Le deuxième chapitre expose la
        spécification fonctionnelle et technique à travers le Sprint 0. Les chapitres trois à six décrivent
        successivement la conception et la réalisation de chacun des quatre sprints : gestion des utilisateurs,
        gestion des patients et rendez-vous, gestion des traitements et documents médicaux, et gestion des stocks
        et fournisseurs. Le rapport se clôture par une conclusion générale récapitulant les apports du projet et
        les perspectives d'évolution envisagées.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 1 : CADRE GÉNÉRAL DU PROJET
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 1 : Cadre général du projet</div>

    <h1 class="chapter-title">Chapitre 1 : Cadre général du projet</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Le présent chapitre se concentre sur le contexte général de notre projet. Nous débutons par une
        présentation de l'organisme d'accueil, suivie de l'exposition de la problématique rencontrée et de la
        solution proposée. Nous concluons ce chapitre par la présentation de la méthodologie de développement
        adoptée pour la réalisation de SmileCare.
    </p>

    <h2>1.1&nbsp;&nbsp;Présentation de l'organisme d'accueil</h2>
    <p>
        Ce projet a été développé en collaboration avec le <b>Cabinet Dentaire « SourisBlanche »</b>, un
        établissement de soins dentaires situé à Tunis. Fondé en 2016, ce cabinet pluridisciplinaire dispose
        d'une équipe de trois chirurgiens-dentistes et de deux secrétaires médicales. Il accueille entre 40 et
        60 patients par jour et propose une gamme complète de soins bucco-dentaires, notamment :
    </p>
    <ul>
        <li><b>Consultations et bilans dentaires :</b> Examens cliniques réguliers, détartrages, radiographies panoramiques et bilan de santé bucco-dentaire.</li>
        <li><b>Soins conservateurs :</b> Traitements des caries, dévitalisations, obturations esthétiques et scellements de sillons.</li>
        <li><b>Prothèses dentaires :</b> Couronnes céramiques, bridges, prothèses amovibles partielles et totales, et implantologie.</li>
        <li><b>Orthodontie :</b> Appareils dentaires fixes et amovibles pour adultes et enfants.</li>
        <li><b>Chirurgie buccale :</b> Extractions simples et complexes, greffes osseuses et traitements parodontaux.</li>
    </ul>

    <h2>1.2&nbsp;&nbsp;Présentation du projet</h2>

    <h3>1.2.1&nbsp;&nbsp;Cadre du projet</h3>
    <p>
        Ce travail s'inscrit dans le cadre du projet de fin d'études pour l'obtention d'une licence en
        développement des systèmes d'information. Il a été réalisé au sein du Cabinet Dentaire SourisBlanche,
        dans l'objectif de répondre à un besoin réel de modernisation de la gestion administrative et médicale
        de l'établissement.
    </p>

    <h3>1.2.2&nbsp;&nbsp;Problématique</h3>
    <p>
        La gestion d'un cabinet dentaire implique un ensemble de tâches administratives et médicales complexes
        qui, lorsqu'elles sont effectuées manuellement ou via des outils non intégrés, génèrent d'importantes
        difficultés. Parmi les problèmes identifiés au sein du cabinet partenaire :
    </p>
    <ul>
        <li><b>Gestion des rendez-vous :</b> La planification se faisait via un agenda papier, entraînant des risques de chevauchement, d'oublis et de difficultés dans la répartition des créneaux entre praticiens.</li>
        <li><b>Dossiers patients :</b> Les informations médicales (antécédents, allergies, traitements) étaient dispersées dans des fichiers papier, rendant leur consultation difficile et risquée.</li>
        <li><b>Facturation :</b> L'établissement des factures et le suivi des paiements nécessitaient une saisie manuelle fastidieuse et sujette aux erreurs de calcul.</li>
        <li><b>Gestion des stocks :</b> Le suivi des consommables et du matériel médical était insuffisant, causant des ruptures de stock inopinées lors des soins.</li>
        <li><b>Documents médicaux :</b> La génération des ordonnances et des bulletins CNAM était chronophage et non standardisée, ralentissant le flux des patients.</li>
    </ul>

    <h3>1.2.3&nbsp;&nbsp;Solution proposée</h3>
    <p>
        Pour répondre à ces problèmes, nous proposons <b>SmileCare</b>, une application web complète de gestion
        de cabinet dentaire. Il s'agit d'une plateforme centralisée accessible via navigateur, qui intègre
        l'ensemble des fonctionnalités nécessaires à la gestion quotidienne d'un cabinet dentaire :
    </p>
    <ul>
        <li>Gestion des rendez-vous avec calendrier interactif et système de notifications automatiques.</li>
        <li>Gestion complète des dossiers patients avec suivi de l'historique médical et des traitements.</li>
        <li>Facturation automatisée avec génération de factures PDF et suivi des paiements.</li>
        <li>Suivi des traitements dentaires par patient et par praticien (numérotation dentaire incluse).</li>
        <li>Génération des ordonnances et bulletins CNAM en format imprimable.</li>
        <li>Gestion des stocks médicaux avec alertes de rupture et commandes fournisseurs.</li>
        <li>Tableaux de bord personnalisés par rôle d'utilisateur (médecin, secrétaire, patient, etc.).</li>
    </ul>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 1 : Cadre général du projet</div>

    <h2>1.3&nbsp;&nbsp;Méthodologie de développement</h2>
    <p>
        Le choix d'une méthodologie de gestion de projet est une étape déterminante pour la réussite d'un
        projet de développement logiciel. Elle offre une structure claire, optimise l'utilisation des ressources,
        minimise les risques et permet de bénéficier de l'expérience accumulée dans le domaine.
    </p>

    <h3>1.3.1&nbsp;&nbsp;Comparatif des méthodologies existantes</h3>
    <p>
        Dans le but de choisir la méthodologie la plus adaptée à notre projet, nous avons réalisé une étude
        comparative entre les principales approches de gestion de projets logiciels, présentée dans le tableau
        suivant :
    </p>

    <p class="tbl-cap">Tableau 1.1 : Comparaison des méthodologies de développement</p>
    <table class="bt">
        <thead>
            <tr>
                <th style="width:18%">Méthodologie</th>
                <th style="width:30%">Description</th>
                <th style="width:27%">Avantages</th>
                <th style="width:25%">Inconvénients</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc">RUP</td>
                <td>Méthodologie de développement logiciel structurée, basée sur une approche descendante, divisée en phases bien définies (inception, élaboration, construction, transition).</td>
                <td>
                    <ul>
                        <li>Approche itérative</li>
                        <li>Prise en compte des risques</li>
                        <li>Documentation complète</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li>Lourdeur de la documentation</li>
                        <li>Coût élevé</li>
                        <li>Planification rigide</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="bc">2TUP</td>
                <td>Méthodologie itérative et incrémentale qui sépare le développement fonctionnel du développement technique, puis les fusionne en une branche d'assemblage.</td>
                <td>
                    <ul>
                        <li>Livraison rapide</li>
                        <li>Flexibilité technique</li>
                        <li>Séparation des préoccupations</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li>Faible documentation</li>
                        <li>Risques de dérive</li>
                        <li>Complexité de fusion</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="bc">SCRUM</td>
                <td>Méthodologie agile reposant sur des cycles itératifs courts (sprints) et des réunions régulières entre les membres de l'équipe pour assurer la progression et l'adaptation continue.</td>
                <td>
                    <ul>
                        <li>Flexibilité et adaptation</li>
                        <li>Approche itérative</li>
                        <li>Communication régulière</li>
                        <li>Livraison incrémentale</li>
                    </ul>
                </td>
                <td>
                    <ul>
                        <li>Forte dépendance à la communication</li>
                        <li>Difficulté à maintenir la documentation</li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>

    <h3>1.3.2&nbsp;&nbsp;Choix de la méthodologie SCRUM</h3>
    <p>
        La méthode SCRUM a été retenue pour le développement de SmileCare, et ce pour plusieurs raisons
        fondamentales. Sa flexibilité permet d'adapter les priorités en cours de développement en fonction
        des retours du client. Son approche itérative garantit la livraison régulière de fonctionnalités
        opérationnelles, facilitant ainsi la validation progressive du produit. De plus, les cérémonies SCRUM
        (réunions quotidiennes, revues et rétrospectives de sprint) favorisent une communication efficace au
        sein de l'équipe et une détection précoce des problèmes.
    </p>

    <h3>1.3.3&nbsp;&nbsp;Mise en pratique de la méthodologie SCRUM</h3>
    <p>Le déroulement de SCRUM dans notre projet suit les étapes suivantes :</p>
    <ol>
        <li><b>Création du backlog produit :</b> Définition de la liste priorisée de toutes les fonctionnalités à réaliser.</li>
        <li><b>Planification du sprint :</b> Sélection des éléments du backlog à traiter lors du sprint suivant.</li>
        <li><b>Exécution du sprint :</b> Développement des fonctionnalités sélectionnées sur une période de 2 à 3 semaines.</li>
        <li><b>Mêlée quotidienne :</b> Courte réunion de synchronisation de l'équipe (15 minutes maximum).</li>
        <li><b>Revue de sprint :</b> Démonstration des fonctionnalités développées au Product Owner.</li>
        <li><b>Rétrospective de sprint :</b> Identification des axes d'amélioration pour le sprint suivant.</li>
    </ol>

    <div class="fig-box">[Figure 1.1 : Déroulement du processus Scrum]</div>
    <p class="fig-cap"><b>Figure 1.1 :</b> Déroulement du processus Scrum</p>

    <p>
        Selon la méthodologie SCRUM, le tableau ci-dessous présente les acteurs impliqués dans notre projet :
    </p>

    <p class="tbl-cap">Tableau 1.2 : Acteurs du projet selon la méthodologie SCRUM</p>
    <table class="bt">
        <thead>
            <tr><th style="width:40%">Rôle</th><th>Acteur</th></tr>
        </thead>
        <tbody>
            <tr><td class="bc">Product Owner</td><td>Directeur du Cabinet Dentaire SourisBlanche</td></tr>
            <tr><td class="bc">Scrum Master</td><td>Encadrant académique – ISET</td></tr>
            <tr><td class="bc">Scrum Team</td><td>L'équipe de développement (étudiants en licence DSI)</td></tr>
        </tbody>
    </table>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Ce chapitre a permis de présenter le cadre général de notre projet, la problématique identifiée au
        sein du cabinet dentaire partenaire, la solution SmileCare que nous proposons, ainsi que la méthode
        SCRUM adoptée pour structurer notre développement. Le chapitre suivant sera consacré à la
        spécification fonctionnelle et technique complète de l'application.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 2 : SPRINT 0 – SPÉCIFICATION FONCTIONNELLE ET TECHNIQUE
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 2 : Sprint 0 – Spécification fonctionnelle et technique</div>

    <h1 class="chapter-title">Chapitre 2 : Sprint 0<br>« Spécification fonctionnelle et technique »</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Ce chapitre est consacré à la spécification détaillée de l'application SmileCare. Nous commençons
        par identifier les acteurs du système et leurs rôles, puis nous exposons les besoins fonctionnels
        et non fonctionnels, nous présentons le backlog produit et le diagramme de cas d'utilisation global,
        avant de décrire l'environnement technique et l'architecture générale de l'application.
    </p>

    <h2>2.1&nbsp;&nbsp;Identification des acteurs</h2>
    <p>
        Dans cette section, nous présentons les différents acteurs de SmileCare ainsi que leurs rôles
        respectifs au sein du système.
    </p>

    <p class="tbl-cap">Tableau 2.1 : Identification des acteurs</p>
    <table class="bt">
        <thead>
            <tr><th style="width:22%">Acteur</th><th>Rôle</th></tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Super Administrateur</b></td>
                <td>Dispose d'un accès total au système. Il gère les utilisateurs, les cabinets, les configurations globales et supervise l'ensemble des opérations de la plateforme.</td>
            </tr>
            <tr>
                <td><b>Administrateur</b></td>
                <td>Gère le personnel du cabinet, supervise les stocks et les commandes fournisseurs, consulte les statistiques et assure la gestion financière (factures, paiements).</td>
            </tr>
            <tr>
                <td><b>Médecin</b></td>
                <td>Consulte et gère son planning de rendez-vous, accède aux dossiers médicaux de ses patients, enregistre les actes réalisés, rédige les ordonnances et les bulletins CNAM.</td>
            </tr>
            <tr>
                <td><b>Secrétaire</b></td>
                <td>Planifie et confirme les rendez-vous, gère l'accueil des patients, établit les factures et assure le suivi administratif des dossiers patients.</td>
            </tr>
            <tr>
                <td><b>Patient</b></td>
                <td>Consulte son planning de rendez-vous, accède à son historique de traitements, visualise ses factures et ses documents médicaux (ordonnances, résultats).</td>
            </tr>
            <tr>
                <td><b>Fournisseur</b></td>
                <td>Reçoit les bons de commande émis par le cabinet, met à jour le statut des livraisons et communique les délais d'approvisionnement.</td>
            </tr>
        </tbody>
    </table>

    <h2>2.2&nbsp;&nbsp;Capture des besoins fonctionnels</h2>
    <p>
        Les besoins fonctionnels de SmileCare sont organisés par acteur. Chaque acteur dispose d'un
        ensemble de fonctionnalités qui lui sont propres, en fonction de son rôle dans le cabinet.
    </p>

    <p><b>Pour le Super Administrateur :</b></p>
    <ul>
        <li><b>Gérer les utilisateurs :</b> Créer, modifier, désactiver et supprimer les comptes utilisateurs (médecins, secrétaires, patients, fournisseurs).</li>
        <li><b>Gérer les cabinets :</b> Configurer les cabinets dentaires, affecter les médecins et secrétaires.</li>
        <li><b>Superviser le système :</b> Accéder à toutes les données et fonctionnalités de l'application.</li>
    </ul>

    <p><b>Pour l'Administrateur :</b></p>
    <ul>
        <li><b>Gérer le personnel :</b> Administrer les comptes du personnel médical et administratif.</li>
        <li><b>Gérer les stocks :</b> Superviser les niveaux de stock, valider les commandes fournisseurs.</li>
        <li><b>Consulter les statistiques :</b> Accéder aux tableaux de bord financiers et opérationnels.</li>
    </ul>

    <p><b>Pour le Médecin :</b></p>
    <ul>
        <li><b>Gérer les rendez-vous :</b> Consulter son planning, confirmer ou annuler les rendez-vous.</li>
        <li><b>Gérer les dossiers patients :</b> Accéder à l'historique médical, aux allergies et aux antécédents.</li>
        <li><b>Enregistrer les traitements :</b> Saisir les actes réalisés, le numéro de dent traité, les notes cliniques.</li>
        <li><b>Rédiger les ordonnances :</b> Créer et imprimer des ordonnances médicamenteuses.</li>
        <li><b>Générer les bulletins CNAM :</b> Produire les feuilles de soins pour la sécurité sociale.</li>
        <li><b>Commander des fournitures :</b> Passer des commandes de matériel médical auprès des fournisseurs.</li>
    </ul>

    <p><b>Pour la Secrétaire :</b></p>
    <ul>
        <li><b>Gérer les rendez-vous :</b> Planifier, confirmer, reporter ou annuler les rendez-vous des patients.</li>
        <li><b>Gérer les patients :</b> Créer et mettre à jour les dossiers administratifs des patients.</li>
        <li><b>Établir les factures :</b> Générer les factures à l'issue des consultations et traitements.</li>
        <li><b>Suivre les paiements :</b> Enregistrer les règlements et gérer les impayés.</li>
    </ul>

    <p><b>Pour le Patient :</b></p>
    <ul>
        <li><b>Consulter ses rendez-vous :</b> Visualiser le calendrier de ses prochains rendez-vous.</li>
        <li><b>Consulter son historique :</b> Accéder à l'historique de ses traitements et actes réalisés.</li>
        <li><b>Consulter ses factures :</b> Visualiser et télécharger ses factures en format PDF.</li>
        <li><b>Consulter ses documents :</b> Accéder à ses ordonnances et bulletins CNAM.</li>
    </ul>

    <p><b>Pour le Fournisseur :</b></p>
    <ul>
        <li><b>Consulter les commandes :</b> Visualiser les bons de commande reçus du cabinet.</li>
        <li><b>Mettre à jour le statut :</b> Indiquer la confirmation, l'expédition et la réception des commandes.</li>
    </ul>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 2 : Sprint 0 – Spécification fonctionnelle et technique</div>

    <h3>2.2.1&nbsp;&nbsp;Diagramme de cas d'utilisation global</h3>
    <p>
        Le diagramme de cas d'utilisation global ci-dessous représente l'ensemble des interactions entre
        les acteurs et le système SmileCare. Il offre une vue synthétique des principales fonctionnalités
        offertes par la plateforme.
    </p>

    <div class="fig-box">[Diagramme de cas d'utilisation global – SmileCare<br>
    Acteurs : Super Admin, Administrateur, Médecin, Secrétaire, Patient, Fournisseur<br>
    Cas d'utilisation principaux : Gérer utilisateurs, Gérer rendez-vous, Gérer patients,<br>
    Gérer traitements, Facturer, Gérer stocks, Commander fournitures, Générer documents]</div>
    <p class="fig-cap"><b>Figure 2.1 :</b> Diagramme de cas d'utilisation global de SmileCare</p>

    <h3>2.2.2&nbsp;&nbsp;Backlog produit</h3>
    <p>
        Le backlog produit présente l'ensemble des user stories organisées par release et par sprint,
        classées selon leur priorité de réalisation.
    </p>

    <p class="tbl-cap">Tableau 2.2 : Backlog produit de SmileCare</p>
    <table class="bt">
        <thead>
            <tr><th style="width:8%">Release</th><th style="width:22%">User Story</th><th>Description</th><th style="width:11%">Priorité</th><th style="width:8%">Sprint</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc" rowspan="4">1</td>
                <td><b>S'authentifier</b></td>
                <td>En tant qu'utilisateur, je veux me connecter à la plateforme afin d'accéder aux fonctionnalités correspondant à mon rôle.</td>
                <td>Élevée</td>
                <td>1</td>
            </tr>
            <tr>
                <td><b>Gérer son profil</b></td>
                <td>En tant qu'utilisateur, je veux mettre à jour mes informations personnelles et changer mon mot de passe.</td>
                <td>Élevée</td>
                <td>1</td>
            </tr>
            <tr>
                <td><b>Gérer les utilisateurs</b></td>
                <td>En tant qu'administrateur, je veux créer, modifier, activer et désactiver les comptes utilisateurs du système.</td>
                <td>Élevée</td>
                <td>1</td>
            </tr>
            <tr>
                <td><b>Gérer les cabinets</b></td>
                <td>En tant que super administrateur, je veux configurer les cabinets et y affecter médecins et secrétaires.</td>
                <td>Moyenne</td>
                <td>1</td>
            </tr>
            <tr>
                <td class="bc" rowspan="3">2</td>
                <td><b>Gérer les patients</b></td>
                <td>En tant que secrétaire ou médecin, je veux créer et gérer les dossiers patients (informations, antécédents, allergies).</td>
                <td>Élevée</td>
                <td>2</td>
            </tr>
            <tr>
                <td><b>Gérer les rendez-vous</b></td>
                <td>En tant que secrétaire, je veux planifier, confirmer et annuler les rendez-vous des patients avec les médecins.</td>
                <td>Élevée</td>
                <td>2</td>
            </tr>
            <tr>
                <td><b>Consulter le calendrier</b></td>
                <td>En tant que médecin, je veux visualiser mon planning de rendez-vous sous forme de calendrier interactif.</td>
                <td>Élevée</td>
                <td>2</td>
            </tr>
            <tr>
                <td class="bc" rowspan="4">3</td>
                <td><b>Gérer les traitements</b></td>
                <td>En tant que médecin, je veux enregistrer les actes réalisés lors d'une consultation, avec le numéro de dent et les notes cliniques.</td>
                <td>Élevée</td>
                <td>3</td>
            </tr>
            <tr>
                <td><b>Facturer les soins</b></td>
                <td>En tant que secrétaire, je veux générer automatiquement une facture à partir des actes enregistrés lors d'un rendez-vous.</td>
                <td>Élevée</td>
                <td>3</td>
            </tr>
            <tr>
                <td><b>Générer ordonnances</b></td>
                <td>En tant que médecin, je veux rédiger et imprimer des ordonnances médicamenteuses pour mes patients.</td>
                <td>Moyenne</td>
                <td>3</td>
            </tr>
            <tr>
                <td><b>Générer bulletins CNAM</b></td>
                <td>En tant que médecin, je veux produire les bulletins de soins CNAM pour la prise en charge par la sécurité sociale.</td>
                <td>Moyenne</td>
                <td>3</td>
            </tr>
            <tr>
                <td class="bc" rowspan="3">4</td>
                <td><b>Gérer les stocks</b></td>
                <td>En tant qu'administrateur, je veux gérer les articles en stock, consulter les niveaux et recevoir des alertes de rupture.</td>
                <td>Moyenne</td>
                <td>4</td>
            </tr>
            <tr>
                <td><b>Gérer les fournisseurs</b></td>
                <td>En tant qu'administrateur, je veux gérer la liste des fournisseurs et leurs informations de contact.</td>
                <td>Moyenne</td>
                <td>4</td>
            </tr>
            <tr>
                <td><b>Gérer les commandes</b></td>
                <td>En tant que médecin ou administrateur, je veux passer des commandes de matériel médical et suivre leur statut de livraison.</td>
                <td>Moyenne</td>
                <td>4</td>
            </tr>
        </tbody>
    </table>

    <h2>2.3&nbsp;&nbsp;Capture des besoins non fonctionnels</h2>
    <ul>
        <li><b>Sécurité :</b> L'application doit garantir la confidentialité des données médicales des patients. Elle met en œuvre un système d'authentification robuste, un contrôle d'accès par rôle (RBAC) et le chiffrement des données sensibles conformément aux réglementations en vigueur.</li>
        <li><b>Performance :</b> La plateforme doit répondre aux requêtes utilisateurs en moins de deux secondes, même en cas de charge importante, grâce à l'optimisation des requêtes et à la mise en cache des données fréquemment consultées.</li>
        <li><b>Disponibilité :</b> L'application doit être accessible 24h/24 et 7j/7 avec un taux de disponibilité minimal de 99 %, assurant ainsi la continuité de service pour le personnel du cabinet.</li>
        <li><b>Convivialité :</b> L'interface utilisateur doit être intuitive, ergonomique et responsive, permettant une prise en main rapide par le personnel soignant et administratif sans formation approfondie.</li>
        <li><b>Fiabilité :</b> Le système doit fonctionner sans interruption ni perte de données, avec des mécanismes de sauvegarde réguliers et un journal des erreurs pour faciliter la maintenance.</li>
        <li><b>Maintenabilité :</b> L'architecture du code doit respecter les bonnes pratiques de développement (MVC, SOLID) afin de faciliter les évolutions futures et la correction des anomalies.</li>
    </ul>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 2 : Sprint 0 – Spécification fonctionnelle et technique</div>

    <h2>2.4&nbsp;&nbsp;Capture des besoins techniques</h2>

    <h3>2.4.1&nbsp;&nbsp;Environnement de développement</h3>
    <p>
        Ci-dessous, nous présentons l'ensemble des technologies utilisées dans la conception et le
        développement de SmileCare.
    </p>

    <p class="tbl-cap">Tableau 2.3 : Environnement de développement</p>
    <table class="bt">
        <thead>
            <tr><th style="width:22%">Technologie</th><th>Description</th></tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Laravel 13</b></td>
                <td>Framework PHP open-source basé sur l'architecture MVC. Il offre un ensemble d'outils puissants pour le développement d'applications web robustes et évolutives, incluant un ORM (Eloquent), un système de routage avancé et un moteur de templates (Blade).</td>
            </tr>
            <tr>
                <td><b>MySQL</b></td>
                <td>Système de gestion de bases de données relationnelles (SGBDR) open-source. Il assure le stockage structuré et sécurisé de l'ensemble des données de l'application (patients, rendez-vous, traitements, factures).</td>
            </tr>
            <tr>
                <td><b>Livewire 3</b></td>
                <td>Bibliothèque Laravel permettant de créer des composants dynamiques côté serveur sans écrire de JavaScript. Elle est utilisée pour les fonctionnalités interactives de l'interface, comme les formulaires en temps réel et les mises à jour dynamiques.</td>
            </tr>
            <tr>
                <td><b>Inertia.js</b></td>
                <td>Couche d'intégration permettant de créer des applications monopage (SPA) en combinant le backend Laravel et des vues côté client, sans avoir besoin d'une API REST dédiée.</td>
            </tr>
            <tr>
                <td><b>Tailwind CSS 4</b></td>
                <td>Framework CSS utilitaire de nouvelle génération permettant de concevoir des interfaces modernes et responsives directement dans le balisage HTML, sans écrire de CSS personnalisé.</td>
            </tr>
            <tr>
                <td><b>Alpine.js</b></td>
                <td>Framework JavaScript léger permettant d'ajouter des comportements interactifs côté client (menus déroulants, modales, transitions) avec une syntaxe déclarative simple inspirée de Vue.js.</td>
            </tr>
            <tr>
                <td><b>Laravel Jetstream</b></td>
                <td>Scaffolding d'authentification et de gestion d'équipe pour Laravel, fournissant la gestion des sessions, la vérification des e-mails et la gestion des profils utilisateurs.</td>
            </tr>
        </tbody>
    </table>

    <h3>2.4.2&nbsp;&nbsp;Environnement logiciel</h3>

    <p class="tbl-cap">Tableau 2.4 : Environnement logiciel</p>
    <table class="bt">
        <thead>
            <tr><th style="width:22%">Outil</th><th>Description</th></tr>
        </thead>
        <tbody>
            <tr>
                <td><b>Visual Studio Code</b></td>
                <td>Éditeur de code léger et puissant développé par Microsoft, utilisé pour l'ensemble du développement de l'application. Il offre des fonctionnalités avancées telles que la coloration syntaxique, l'auto-complétion, la détection d'erreurs en temps réel et l'intégration Git.</td>
            </tr>
            <tr>
                <td><b>Laragon</b></td>
                <td>Environnement de développement web local pour Windows, intégrant PHP, MySQL, Apache/Nginx et Node.js. Il permet de configurer rapidement un serveur de développement local pour tester l'application.</td>
            </tr>
            <tr>
                <td><b>Git / GitHub</b></td>
                <td>Système de contrôle de version distribué utilisé pour gérer le code source du projet, faciliter la collaboration et assurer la traçabilité des modifications apportées au fil du développement.</td>
            </tr>
            <tr>
                <td><b>Composer</b></td>
                <td>Gestionnaire de dépendances PHP permettant d'installer et de gérer les bibliothèques tierces utilisées dans le projet (Laravel, dompdf, Livewire, etc.).</td>
            </tr>
            <tr>
                <td><b>npm / Vite</b></td>
                <td>Gestionnaire de paquets JavaScript (npm) et outil de build moderne (Vite) pour la compilation des assets front-end (CSS Tailwind, Alpine.js).</td>
            </tr>
        </tbody>
    </table>

    <h3>2.4.3&nbsp;&nbsp;Architecture générale de l'application</h3>
    <p>
        SmileCare est développée selon l'architecture <b>Modèle-Vue-Contrôleur (MVC)</b>, nativement supportée
        par le framework Laravel. Cette architecture sépare clairement les responsabilités du système en trois
        couches distinctes :
    </p>
    <ul>
        <li><b>Modèle (Model) :</b> Représente la logique métier et les données de l'application. Les modèles Eloquent de Laravel interagissent avec la base de données MySQL et encapsulent les règles de validation et les relations entre entités.</li>
        <li><b>Vue (View) :</b> Représente la couche de présentation. Les vues Blade de Laravel génèrent le HTML envoyé au navigateur de l'utilisateur, intégrant Tailwind CSS pour le style et Alpine.js pour les interactions dynamiques.</li>
        <li><b>Contrôleur (Controller) :</b> Fait le lien entre le Modèle et la Vue. Il reçoit les requêtes HTTP, applique la logique applicative, interagit avec les modèles et retourne la vue appropriée à l'utilisateur.</li>
    </ul>

    <div class="fig-box">[Figure 2.2 : Architecture MVC de SmileCare<br>
    Client (Navigateur) → HTTP Request → Serveur Web (Laravel) → Controller → Model (Eloquent ORM) → MySQL<br>
    MySQL → Model → Controller → View (Blade + Tailwind) → HTTP Response → Client]</div>
    <p class="fig-cap"><b>Figure 2.2 :</b> Architecture physique de SmileCare</p>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Dans ce chapitre, nous avons défini les acteurs du système et leurs besoins fonctionnels, établi
        le backlog produit complet organisé en quatre sprints, précisé les exigences non fonctionnelles
        et présenté l'environnement technique retenu ainsi que l'architecture MVC de l'application. Le
        chapitre suivant sera consacré à l'étude et à la réalisation du premier sprint.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 3 : SPRINT 1 – AUTHENTIFICATION ET GESTION DES UTILISATEURS
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 3 : Étude et réalisation du Sprint 1</div>

    <h1 class="chapter-title">Chapitre 3 : Étude et réalisation du Sprint 1</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Ce chapitre décrit la mise en œuvre du premier sprint de SmileCare, consacré à l'authentification
        des utilisateurs et à la gestion des comptes. Chaque fonctionnalité du sprint est présentée selon
        une procédure structurée comprenant la spécification fonctionnelle, la conception et la réalisation.
    </p>

    <h2>3.1&nbsp;&nbsp;Sprint 1 « Authentification et gestion des utilisateurs »</h2>

    <h3>3.1.1&nbsp;&nbsp;Backlog du Sprint 1</h3>

    <p class="tbl-cap">Tableau 3.1 : Backlog du Sprint 1</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Nom</th><th>Description</th><th style="width:15%">Estimation</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc">S'authentifier</td>
                <td>En tant qu'utilisateur, je veux me connecter à la plateforme avec mon adresse e-mail et mon mot de passe afin d'accéder aux fonctionnalités correspondant à mon rôle.</td>
                <td>3 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer son profil</td>
                <td>En tant qu'utilisateur, je veux consulter et mettre à jour mes informations personnelles (nom, prénom, photo, téléphone) et modifier mon mot de passe.</td>
                <td>2 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les utilisateurs</td>
                <td>En tant qu'administrateur, je veux créer des comptes pour les médecins, secrétaires, patients et fournisseurs, et gérer leur accès (activation/désactivation).</td>
                <td>4 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les cabinets</td>
                <td>En tant que super administrateur, je veux créer et configurer les cabinets dentaires en y associant un médecin référent et une secrétaire.</td>
                <td>3 jours</td>
            </tr>
        </tbody>
    </table>

    <h3>3.1.2&nbsp;&nbsp;Spécification fonctionnelle</h3>

    <h4>3.1.2.1&nbsp;&nbsp;Diagramme de cas d'utilisation du Sprint 1</h4>
    <p>
        La figure 3.1 ci-dessous présente le diagramme de cas d'utilisation du Sprint 1, illustrant les
        interactions entre les différents acteurs et les fonctionnalités d'authentification et de gestion
        des utilisateurs.
    </p>

    <div class="fig-box">[Diagramme de cas d'utilisation – Sprint 1<br>
    Acteur : Utilisateur (S'authentifier, Gérer profil, Réinitialiser mot de passe)<br>
    Acteur : Administrateur (Gérer utilisateurs → inclut S'authentifier)<br>
    Acteur : Super Administrateur (Gérer cabinets → inclut S'authentifier)]</div>
    <p class="fig-cap"><b>Figure 3.1 :</b> Diagramme de cas d'utilisation du Sprint 1</p>

    <h4>3.1.2.2&nbsp;&nbsp;Description textuelle des cas d'utilisation du Sprint 1</h4>

    <p class="tbl-cap">Tableau 3.2 : Description textuelle du cas d'utilisation « S'authentifier »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>S'authentifier</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Vérifier l'identité de l'utilisateur avant de lui accorder l'accès aux fonctionnalités de la plateforme correspondant à son rôle.</td></tr>
            <tr><td><b>Acteur</b></td><td>Tous les utilisateurs du système</td></tr>
            <tr><td><b>Précondition</b></td><td>L'utilisateur dispose d'un compte actif avec une adresse e-mail et un mot de passe valides enregistrés dans le système.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. L'utilisateur accède à la page de connexion.<br>
                2. Il saisit son adresse e-mail et son mot de passe.<br>
                3. Le système vérifie les identifiants saisis.<br>
                4. L'utilisateur est authentifié et redirigé vers son tableau de bord personnalisé.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>
                3.1 – Les identifiants sont incorrects : un message d'erreur s'affiche.<br>
                3.2 – Le compte est désactivé : l'accès est refusé avec un message explicatif.<br>
                3.3 – L'utilisateur a oublié son mot de passe : il peut initier la procédure de réinitialisation.
            </td></tr>
            <tr><td><b>Post-condition</b></td><td>L'utilisateur est connecté et accède aux fonctionnalités correspondant à son rôle.</td></tr>
        </tbody>
    </table>

    <p class="tbl-cap">Tableau 3.3 : Description textuelle du cas d'utilisation « Gérer les utilisateurs »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>Gérer les utilisateurs</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Permettre à l'administrateur de contrôler l'accès au système en gérant les comptes utilisateurs.</td></tr>
            <tr><td><b>Acteur</b></td><td>Administrateur, Super Administrateur</td></tr>
            <tr><td><b>Précondition</b></td><td>L'administrateur est authentifié et dispose des droits de gestion des utilisateurs.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. L'administrateur accède à la liste des utilisateurs.<br>
                2. Il choisit de créer un nouveau compte en renseignant les informations (nom, e-mail, rôle).<br>
                3. Le système crée le compte et envoie les identifiants à l'utilisateur.<br>
                4. L'administrateur peut modifier le rôle, activer ou désactiver un compte existant.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>2.1 – L'adresse e-mail est déjà utilisée : un message d'erreur s'affiche indiquant le doublon.</td></tr>
            <tr><td><b>Post-condition</b></td><td>Le compte est créé ou mis à jour avec succès dans le système.</td></tr>
        </tbody>
    </table>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 3 : Étude et réalisation du Sprint 1</div>

    <h3>3.1.3&nbsp;&nbsp;Conception</h3>

    <h4>3.1.3.1&nbsp;&nbsp;Diagramme de séquence « S'authentifier »</h4>
    <div class="fig-box">[Diagramme de séquence – S'authentifier<br>
    Utilisateur → LoginView : saisir email et mot de passe<br>
    LoginView → AuthController : envoyer credentials<br>
    AuthController → UserModel : rechercher(email, password)<br>
    UserModel → AuthController : retourner résultat<br>
    [Si valide] → Rediriger vers dashboard | [Si invalide] → Afficher message d'erreur]</div>
    <p class="fig-cap"><b>Figure 3.2 :</b> Diagramme de séquence du cas d'utilisation « S'authentifier »</p>

    <h4>3.1.3.2&nbsp;&nbsp;Diagramme de séquence « Gérer les utilisateurs »</h4>
    <div class="fig-box">[Diagramme de séquence – Gérer les utilisateurs<br>
    Administrateur → UserListView : accéder à la liste<br>
    Administrateur → CreateUserForm : remplir formulaire<br>
    CreateUserForm → UserController : créerUtilisateur(données, token)<br>
    UserController → UserModel : valider et sauvegarder<br>
    UserModel → UserController : retourner résultat<br>
    UserController → UserListView : afficher confirmation]</div>
    <p class="fig-cap"><b>Figure 3.3 :</b> Diagramme de séquence du cas d'utilisation « Gérer les utilisateurs »</p>

    <h4>3.1.3.3&nbsp;&nbsp;Diagramme de classes du Sprint 1</h4>
    <div class="fig-box">[Diagramme de classes – Sprint 1<br>
    User : -id, -name, -email, -password, -role, -phone, -is_active<br>
    +sAuthentifier(), +mettreAJourProfil(), +reinitialiserMotDePasse()<br><br>
    DoctorProfile : -user_id, -specialization, -license_number, -working_days<br>
    PatientProfile : -user_id, -date_of_birth, -blood_type, -allergies, -cnam_id<br>
    Cabinet : -name, -doctor_id, -secretary_id, -is_active]</div>
    <p class="fig-cap"><b>Figure 3.4 :</b> Diagramme de classes du Sprint 1</p>

    <h3>3.1.4&nbsp;&nbsp;Réalisation du Sprint 1</h3>
    <p>
        Cette section présente les interfaces développées lors du Sprint 1, illustrant les fonctionnalités
        d'authentification et de gestion des utilisateurs.
    </p>

    <p>
        La figure 3.5 illustre la page de connexion de SmileCare. L'utilisateur saisit son adresse e-mail
        et son mot de passe pour accéder à la plateforme. Un lien « Mot de passe oublié » est disponible
        pour initier la procédure de réinitialisation.
    </p>
    <div class="fig-box">[Capture d'écran – Interface de connexion (login)<br>
    Champs : Adresse e-mail, Mot de passe | Bouton : Se connecter | Lien : Mot de passe oublié ?]</div>
    <p class="fig-cap"><b>Figure 3.5 :</b> Interface de connexion à SmileCare</p>

    <p>
        La figure 3.6 montre l'interface de gestion des utilisateurs accessible aux administrateurs. Elle
        présente la liste complète des utilisateurs avec leur rôle, leur statut (actif/inactif) et les
        actions disponibles (modifier, désactiver, supprimer).
    </p>
    <div class="fig-box">[Capture d'écran – Interface de gestion des utilisateurs<br>
    Tableau : Nom | Prénom | Email | Rôle | Statut | Actions (Modifier / Désactiver)]</div>
    <p class="fig-cap"><b>Figure 3.6 :</b> Interface de gestion des utilisateurs</p>

    <p>
        La figure 3.7 présente le tableau de bord personnalisé du médecin, qui affiche un résumé des
        rendez-vous du jour, les patients récents et les alertes de stock, adapté à son rôle.
    </p>
    <div class="fig-box">[Capture d'écran – Tableau de bord Médecin<br>
    Widgets : Rendez-vous du jour | Patients récents | Alertes stock | Statistiques mensuelles]</div>
    <p class="fig-cap"><b>Figure 3.7 :</b> Tableau de bord du médecin</p>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Dans ce chapitre, nous avons présenté la conception et la réalisation du Sprint 1, couvrant
        l'authentification des utilisateurs, la gestion des profils, la gestion des comptes par
        l'administrateur et la configuration des cabinets. Le chapitre suivant sera consacré au Sprint 2,
        dédié à la gestion des patients et des rendez-vous.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 4 : SPRINT 2 – GESTION DES PATIENTS ET DES RENDEZ-VOUS
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 4 : Étude et réalisation du Sprint 2</div>

    <h1 class="chapter-title">Chapitre 4 : Étude et réalisation du Sprint 2</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Ce chapitre porte sur la conception et le développement du Sprint 2, qui couvre deux modules
        essentiels de SmileCare : la gestion des dossiers patients et la gestion des rendez-vous. Ces
        deux fonctionnalités constituent le cœur opérationnel du système et répondent directement aux
        besoins quotidiens des secrétaires et des médecins du cabinet.
    </p>

    <h2>4.1&nbsp;&nbsp;Sprint 2 « Gestion des patients et des rendez-vous »</h2>

    <h3>4.1.1&nbsp;&nbsp;Backlog du Sprint 2</h3>

    <p class="tbl-cap">Tableau 4.1 : Backlog du Sprint 2</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Nom</th><th>Description</th><th style="width:15%">Estimation</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc">Gérer les patients</td>
                <td>En tant que secrétaire ou médecin, je veux créer et gérer les dossiers patients incluant informations personnelles, antécédents médicaux, allergies et coordonnées d'urgence.</td>
                <td>5 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les rendez-vous</td>
                <td>En tant que secrétaire, je veux planifier, confirmer, reporter ou annuler les rendez-vous des patients et notifier automatiquement les médecins concernés.</td>
                <td>6 jours</td>
            </tr>
            <tr>
                <td class="bc">Consulter le calendrier</td>
                <td>En tant que médecin, je veux visualiser mon planning sous forme de calendrier interactif (vue journalière, hebdomadaire, mensuelle) avec les détails de chaque rendez-vous.</td>
                <td>4 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les notifications</td>
                <td>En tant qu'utilisateur, je veux recevoir des notifications en temps réel pour les nouveaux rendez-vous, les modifications et les rappels importants.</td>
                <td>3 jours</td>
            </tr>
        </tbody>
    </table>

    <h3>4.1.2&nbsp;&nbsp;Spécification fonctionnelle</h3>

    <h4>4.1.2.1&nbsp;&nbsp;Diagramme de cas d'utilisation du Sprint 2</h4>
    <div class="fig-box">[Diagramme de cas d'utilisation – Sprint 2<br>
    Acteur Secrétaire : Gérer patients (Créer, Modifier, Afficher), Gérer rendez-vous (Planifier, Confirmer, Annuler, Reporter)<br>
    Acteur Médecin : Consulter calendrier, Accéder dossier patient<br>
    Acteur Patient : Consulter ses rendez-vous<br>
    Relations : Gérer rendez-vous inclut S'authentifier]</div>
    <p class="fig-cap"><b>Figure 4.1 :</b> Diagramme de cas d'utilisation du Sprint 2</p>

    <h4>4.1.2.2&nbsp;&nbsp;Description textuelle des cas d'utilisation du Sprint 2</h4>

    <p class="tbl-cap">Tableau 4.2 : Description textuelle du cas d'utilisation « Planifier un rendez-vous »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>Planifier un rendez-vous</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Permettre à la secrétaire de réserver un créneau horaire pour un patient auprès d'un médecin disponible, avec attribution d'un cabinet.</td></tr>
            <tr><td><b>Acteur</b></td><td>Secrétaire</td></tr>
            <tr><td><b>Précondition</b></td><td>La secrétaire est authentifiée. Le patient et le médecin existent dans le système. Le créneau horaire choisi est disponible.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. La secrétaire accède au formulaire de création de rendez-vous.<br>
                2. Elle sélectionne le patient, le médecin, la date, l'heure et la durée.<br>
                3. Elle précise le type de rendez-vous (consultation, soin, contrôle, urgence).<br>
                4. Le système vérifie la disponibilité du médecin et du cabinet.<br>
                5. Le rendez-vous est enregistré avec le statut « En attente » et une notification est envoyée.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>4.1 – Le créneau est déjà occupé : le système affiche un message d'erreur et propose des créneaux alternatifs.</td></tr>
            <tr><td><b>Post-condition</b></td><td>Le rendez-vous est créé et visible dans le calendrier du médecin et dans l'espace patient.</td></tr>
        </tbody>
    </table>

    <p class="tbl-cap">Tableau 4.3 : Description textuelle du cas d'utilisation « Gérer les dossiers patients »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>Gérer les dossiers patients</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Centraliser toutes les informations médicales et administratives d'un patient pour faciliter le suivi et la prise en charge.</td></tr>
            <tr><td><b>Acteur</b></td><td>Secrétaire, Médecin</td></tr>
            <tr><td><b>Précondition</b></td><td>L'utilisateur est authentifié et dispose des droits d'accès aux dossiers patients.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. L'utilisateur accède à la liste des patients.<br>
                2. Il crée un nouveau dossier patient en renseignant les informations personnelles, médicales et d'assurance.<br>
                3. Le système valide et enregistre les données.<br>
                4. Le dossier est accessible et modifiable à tout moment par le personnel autorisé.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>2.1 – Un champ obligatoire est manquant : un message de validation s'affiche indiquant le champ concerné.</td></tr>
            <tr><td><b>Post-condition</b></td><td>Le dossier patient est créé ou mis à jour avec succès dans le système.</td></tr>
        </tbody>
    </table>

    <h3>4.1.3&nbsp;&nbsp;Conception</h3>

    <h4>4.1.3.1&nbsp;&nbsp;Diagramme de séquence « Planifier un rendez-vous »</h4>
    <div class="fig-box">[Diagramme de séquence – Planifier un rendez-vous<br>
    Secrétaire → AppointmentView : remplir formulaire<br>
    AppointmentView → AppointmentController : créerRendezVous(données, token)<br>
    AppointmentController → AppointmentModel : vérifierDisponibilité()<br>
    AppointmentModel → Controller : disponible<br>
    Controller → AppointmentModel : sauvegarder()<br>
    Controller → NotificationService : notifier médecin et patient<br>
    Controller → AppointmentView : confirmer création]</div>
    <p class="fig-cap"><b>Figure 4.2 :</b> Diagramme de séquence « Planifier un rendez-vous »</p>

    <h4>4.1.3.2&nbsp;&nbsp;Diagramme de classes du Sprint 2</h4>
    <div class="fig-box">[Diagramme de classes – Sprint 2<br>
    User → PatientProfile : 1..1 | User → DoctorProfile : 1..1<br>
    Appointment : -patient_id, -doctor_id, -cabinet_id, -appointment_date, -duration_minutes, -status, -type<br>
    Appointment statuts : pending | confirmed | in_progress | completed | cancelled | no_show<br>
    Appointment types : checkup | consultation | procedure | follow_up | emergency<br>
    PatientProfile : -date_of_birth, -blood_type, -allergies, -cnam_id, -insurance_provider<br>
    Cabinet : -name, -doctor_id, -secretary_id]</div>
    <p class="fig-cap"><b>Figure 4.3 :</b> Diagramme de classes du Sprint 2</p>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 4 : Étude et réalisation du Sprint 2</div>

    <h3>4.1.4&nbsp;&nbsp;Réalisation du Sprint 2</h3>

    <p>
        La figure 4.4 présente la liste des patients avec les informations clés (nom, prénom, téléphone,
        groupe sanguin, numéro CNAM) et les options d'action (consulter, modifier, accéder au dossier médical).
    </p>
    <div class="fig-box">[Capture d'écran – Liste des patients<br>
    Tableau : Nom | Prénom | Téléphone | Groupe sanguin | CNAM | Dernier RDV | Actions]</div>
    <p class="fig-cap"><b>Figure 4.4 :</b> Interface de gestion des patients</p>

    <p>
        La figure 4.5 illustre le formulaire de création de rendez-vous, permettant à la secrétaire de
        sélectionner le patient, le médecin, le type de rendez-vous, la date et l'heure souhaitées.
    </p>
    <div class="fig-box">[Capture d'écran – Formulaire de création de rendez-vous<br>
    Champs : Patient (liste déroulante) | Médecin | Type (checkup/consultation/soin/urgence)<br>
    Date et heure | Durée (minutes) | Motif | Notes | Cabinet]</div>
    <p class="fig-cap"><b>Figure 4.5 :</b> Interface de création d'un rendez-vous</p>

    <p>
        La figure 4.6 montre le calendrier interactif du médecin affichant ses rendez-vous planifiés,
        avec des codes couleur selon le type et le statut de chaque rendez-vous.
    </p>
    <div class="fig-box">[Capture d'écran – Calendrier des rendez-vous<br>
    Vue hebdomadaire avec créneaux colorés par type :<br>
    Bleu = Consultation | Vert = Contrôle | Orange = Soin | Rouge = Urgence]</div>
    <p class="fig-cap"><b>Figure 4.6 :</b> Calendrier des rendez-vous du médecin</p>

    <p>
        La figure 4.7 présente la fiche détaillée d'un patient, regroupant ses informations médicales,
        son historique de rendez-vous et de traitements, ainsi que ses documents médicaux.
    </p>
    <div class="fig-box">[Capture d'écran – Fiche patient détaillée<br>
    Sections : Informations personnelles | Antécédents médicaux | Allergies<br>
    Historique des rendez-vous | Traitements réalisés | Factures | Documents médicaux]</div>
    <p class="fig-cap"><b>Figure 4.7 :</b> Fiche détaillée d'un patient</p>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Ce chapitre a décrit la conception et la réalisation du Sprint 2, couvrant la gestion complète
        des dossiers patients et le système de planification des rendez-vous avec calendrier interactif.
        Le Sprint 3, présenté dans le chapitre suivant, sera consacré à la gestion des traitements,
        de la facturation et des documents médicaux.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 5 : SPRINT 3 – TRAITEMENTS, FACTURES ET DOCUMENTS MÉDICAUX
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 5 : Étude et réalisation du Sprint 3</div>

    <h1 class="chapter-title">Chapitre 5 : Étude et réalisation du Sprint 3</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Ce chapitre présente le Sprint 3 de SmileCare, dédié aux fonctionnalités médicales et financières
        de l'application. Il couvre l'enregistrement des actes de soins, la génération automatique des
        factures, la rédaction des ordonnances médicales et la production des bulletins CNAM pour la prise
        en charge par la caisse nationale d'assurance maladie.
    </p>

    <h2>5.1&nbsp;&nbsp;Sprint 3 « Traitements, Factures et Documents médicaux »</h2>

    <h3>5.1.1&nbsp;&nbsp;Backlog du Sprint 3</h3>

    <p class="tbl-cap">Tableau 5.1 : Backlog du Sprint 3</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Nom</th><th>Description</th><th style="width:15%">Estimation</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc">Gérer les traitements</td>
                <td>En tant que médecin, je veux enregistrer les actes de soins réalisés lors d'une consultation en précisant le type de traitement, le numéro de dent, le coût et les notes cliniques.</td>
                <td>5 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les catégories de traitements</td>
                <td>En tant qu'administrateur, je veux créer et gérer les catégories de traitements dentaires (conservateur, prothèse, chirurgie, orthodontie) et y associer des actes avec leurs tarifs.</td>
                <td>3 jours</td>
            </tr>
            <tr>
                <td class="bc">Facturer les soins</td>
                <td>En tant que secrétaire, je veux générer automatiquement une facture à partir des actes enregistrés, appliquer des remises, calculer la TVA et imprimer la facture en PDF.</td>
                <td>5 jours</td>
            </tr>
            <tr>
                <td class="bc">Générer les ordonnances</td>
                <td>En tant que médecin, je veux rédiger une ordonnance médicamenteuse pour mon patient, incluant les médicaments, les dosages et la posologie, et l'imprimer.</td>
                <td>3 jours</td>
            </tr>
            <tr>
                <td class="bc">Générer les bulletins CNAM</td>
                <td>En tant que médecin, je veux produire un bulletin de soins CNAM reprenant les actes dentaires réalisés et les prothèses posées, pour la prise en charge sociale du patient.</td>
                <td>4 jours</td>
            </tr>
        </tbody>
    </table>

    <h3>5.1.2&nbsp;&nbsp;Spécification fonctionnelle</h3>

    <h4>5.1.2.1&nbsp;&nbsp;Diagramme de cas d'utilisation du Sprint 3</h4>
    <div class="fig-box">[Diagramme de cas d'utilisation – Sprint 3<br>
    Acteur Médecin : Enregistrer traitement, Rédiger ordonnance, Générer bulletin CNAM<br>
    Acteur Secrétaire : Créer facture, Marquer facture payée, Imprimer facture<br>
    Acteur Patient : Consulter ses traitements, Consulter ses factures<br>
    Relations : Créer facture inclut Enregistrer traitement | Générer CNAM étend Enregistrer traitement]</div>
    <p class="fig-cap"><b>Figure 5.1 :</b> Diagramme de cas d'utilisation du Sprint 3</p>

    <h4>5.1.2.2&nbsp;&nbsp;Description textuelle des cas d'utilisation du Sprint 3</h4>

    <p class="tbl-cap">Tableau 5.2 : Description textuelle du cas d'utilisation « Facturer les soins »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>Facturer les soins</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Générer automatiquement une facture à partir des actes de soins enregistrés lors d'un rendez-vous, avec gestion des remises, taxes et modes de paiement.</td></tr>
            <tr><td><b>Acteur</b></td><td>Secrétaire, Médecin</td></tr>
            <tr><td><b>Précondition</b></td><td>Le rendez-vous est terminé et les actes de soins correspondants ont été enregistrés par le médecin.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. La secrétaire accède au rendez-vous terminé et sélectionne « Créer facture ».<br>
                2. Le système pré-remplit la facture avec les actes enregistrés et leurs tarifs.<br>
                3. La secrétaire vérifie les lignes, applique éventuellement une remise.<br>
                4. Le système calcule le sous-total, la TVA et le montant total.<br>
                5. La facture est enregistrée avec le numéro automatique (INV-XXXXX) et le statut « Émise ».<br>
                6. La secrétaire peut imprimer ou télécharger la facture en PDF.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>2.1 – Aucun acte enregistré : le système avertit que la facture ne peut être générée sans actes associés.</td></tr>
            <tr><td><b>Post-condition</b></td><td>La facture est créée, visible dans l'espace patient et dans la liste des factures du cabinet.</td></tr>
        </tbody>
    </table>

    <h3>5.1.3&nbsp;&nbsp;Conception</h3>

    <h4>5.1.3.1&nbsp;&nbsp;Diagramme de séquence « Créer une facture »</h4>
    <div class="fig-box">[Diagramme de séquence – Créer une facture<br>
    Secrétaire → InvoiceController : créerFacture(appointmentId, token)<br>
    InvoiceController → TreatmentRecordModel : getTreatmentsByAppointment()<br>
    TreatmentRecordModel → InvoiceController : retourner actes<br>
    InvoiceController → InvoiceModel : sauvegarder(invoice_number, sous-total, TVA, total)<br>
    InvoiceModel → InvoiceItemModel : sauvegarder lignes<br>
    InvoiceController → PDFService : générer PDF<br>
    InvoiceController → InvoiceView : afficher facture]</div>
    <p class="fig-cap"><b>Figure 5.2 :</b> Diagramme de séquence « Créer une facture »</p>

    <h4>5.1.3.2&nbsp;&nbsp;Diagramme de classes du Sprint 3</h4>
    <div class="fig-box">[Diagramme de classes – Sprint 3<br>
    Invoice : -invoice_number (INV-#####), -patient_id, -appointment_id<br>
    -subtotal, -discount, -tax, -total, -status (draft|issued|paid|overdue|cancelled), -due_date<br>
    InvoiceItem : -invoice_id, -treatment_id, -description, -quantity, -unit_price, -subtotal<br>
    Treatment : -category_id, -name, -duration_minutes, -price<br>
    TreatmentRecord : -patient_id, -doctor_id, -appointment_id, -tooth_number, -status, -cost<br>
    Ordonnance : -appointment_id, -items[] (médicaments), -notes<br>
    CnamBulletin : -appointment_id, -dental_acts[], -prostheses[]]</div>
    <p class="fig-cap"><b>Figure 5.3 :</b> Diagramme de classes du Sprint 3</p>
</div>
<div class="pb"></div>

<div class="page">
    <div class="chap-hdr">Chapitre 5 : Étude et réalisation du Sprint 3</div>

    <h3>5.1.4&nbsp;&nbsp;Réalisation du Sprint 3</h3>

    <p>
        La figure 5.4 illustre l'interface d'enregistrement des traitements, permettant au médecin de
        saisir les actes réalisés lors de la consultation avec la sélection du numéro de dent traité.
    </p>
    <div class="fig-box">[Capture d'écran – Enregistrement d'un traitement<br>
    Champs : Patient | Médecin | Rendez-vous associé | Type de traitement (catégorie → acte)<br>
    Numéro de dent | Statut (planifié/en cours/terminé/annulé) | Date | Coût | Notes cliniques]</div>
    <p class="fig-cap"><b>Figure 5.4 :</b> Interface d'enregistrement d'un traitement</p>

    <p>
        La figure 5.5 présente la facture générée automatiquement à partir des actes de soins. Elle
        affiche le numéro de facture unique (INV-XXXXX), le détail des lignes, les remises appliquées,
        la TVA et le montant total dû.
    </p>
    <div class="fig-box">[Capture d'écran – Facture générée<br>
    En-tête : Logo cabinet | Numéro de facture (INV-00001) | Date d'émission | Date d'échéance<br>
    Tableau : Description | Quantité | Prix unitaire | Sous-total<br>
    Pied : Sous-total | Remise | TVA (19%) | Total TTC | Statut de paiement]</div>
    <p class="fig-cap"><b>Figure 5.5 :</b> Aperçu d'une facture générée par SmileCare</p>

    <p>
        La figure 5.6 montre l'interface de génération d'une ordonnance médicale, permettant au médecin
        de saisir les médicaments prescrits avec leurs dosages et la durée du traitement.
    </p>
    <div class="fig-box">[Capture d'écran – Interface de création d'ordonnance<br>
    Lignes médicaments : Nom du médicament | Dosage | Posologie | Durée<br>
    Notes complémentaires | Boutons : Ajouter médicament | Imprimer ordonnance]</div>
    <p class="fig-cap"><b>Figure 5.6 :</b> Interface de création d'ordonnance</p>

    <p>
        La figure 5.7 illustre le formulaire de saisie du bulletin CNAM permettant au médecin de
        documenter les actes dentaires réalisés pour la prise en charge par l'assurance maladie.
    </p>
    <div class="fig-box">[Capture d'écran – Bulletin CNAM<br>
    En-tête : Informations patient | Numéro CNAM | Type d'assuré<br>
    Tableau actes dentaires | Section prothèses | Signature médecin | Date de soins]</div>
    <p class="fig-cap"><b>Figure 5.7 :</b> Interface de génération du bulletin CNAM</p>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Ce chapitre a présenté la conception et la réalisation du Sprint 3, couvrant l'enregistrement
        des actes de soins, la facturation automatisée, la génération des ordonnances et la production
        des bulletins CNAM. Le Sprint 4, présenté dans le chapitre suivant, sera consacré à la gestion
        des stocks et des fournisseurs.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CHAPITRE 6 : SPRINT 4 – GESTION DES STOCKS ET DES FOURNISSEURS
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Chapitre 6 : Étude et réalisation du Sprint 4</div>

    <h1 class="chapter-title">Chapitre 6 : Étude et réalisation du Sprint 4</h1>

    <p class="hd-intro"><b>Introduction</b></p>
    <p>
        Ce dernier chapitre de développement est consacré au Sprint 4, qui couvre la gestion de la
        chaîne d'approvisionnement du cabinet dentaire. Il comprend la gestion des articles en stock,
        le suivi des niveaux avec alertes de rupture, la gestion des fournisseurs et le processus complet
        de commande de matériel médical.
    </p>

    <h2>6.1&nbsp;&nbsp;Sprint 4 « Gestion des stocks et des fournisseurs »</h2>

    <h3>6.1.1&nbsp;&nbsp;Backlog du Sprint 4</h3>

    <p class="tbl-cap">Tableau 6.1 : Backlog du Sprint 4</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Nom</th><th>Description</th><th style="width:15%">Estimation</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="bc">Gérer les articles en stock</td>
                <td>En tant qu'administrateur, je veux créer et gérer la liste des articles médicaux avec leur référence, leur prix unitaire, leur stock actuel et leur seuil minimal d'alerte.</td>
                <td>4 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les catégories de stock</td>
                <td>En tant qu'administrateur, je veux organiser les articles en catégories (instruments, consommables, prothèses, médicaments) pour faciliter la recherche et le suivi.</td>
                <td>2 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les fournisseurs</td>
                <td>En tant qu'administrateur, je veux créer et gérer la liste des fournisseurs avec leurs coordonnées, afin de les associer aux articles qu'ils approvisionnent.</td>
                <td>3 jours</td>
            </tr>
            <tr>
                <td class="bc">Gérer les commandes fournisseurs</td>
                <td>En tant que médecin ou administrateur, je veux créer des bons de commande, les envoyer aux fournisseurs et suivre leur statut (brouillon, envoyée, confirmée, expédiée, reçue).</td>
                <td>5 jours</td>
            </tr>
            <tr>
                <td class="bc">Alertes de stock</td>
                <td>En tant qu'administrateur ou médecin, je veux recevoir des alertes automatiques lorsque le niveau d'un article atteint ou dépasse son seuil minimal, afin de lancer une commande à temps.</td>
                <td>2 jours</td>
            </tr>
        </tbody>
    </table>

    <h3>6.1.2&nbsp;&nbsp;Spécification fonctionnelle</h3>

    <h4>6.1.2.1&nbsp;&nbsp;Diagramme de cas d'utilisation du Sprint 4</h4>
    <div class="fig-box">[Diagramme de cas d'utilisation – Sprint 4<br>
    Acteur Administrateur : Gérer articles, Gérer catégories, Gérer fournisseurs, Passer commande, Recevoir alerte stock<br>
    Acteur Médecin : Passer commande, Consulter stock, Recevoir alerte<br>
    Acteur Fournisseur : Consulter commandes reçues, Mettre à jour statut livraison<br>
    Relations : Passer commande inclut S'authentifier | Passer commande étend Alerte stock]</div>
    <p class="fig-cap"><b>Figure 6.1 :</b> Diagramme de cas d'utilisation du Sprint 4</p>

    <h4>6.1.2.2&nbsp;&nbsp;Description textuelle des cas d'utilisation du Sprint 4</h4>

    <p class="tbl-cap">Tableau 6.2 : Description textuelle du cas d'utilisation « Gérer les commandes fournisseurs »</p>
    <table class="bt">
        <thead>
            <tr><th style="width:28%">Cas d'utilisation</th><th>Gérer les commandes fournisseurs</th></tr>
        </thead>
        <tbody>
            <tr><td><b>Intérêts</b></td><td>Permettre au médecin ou à l'administrateur de créer et de suivre les bons de commande de matériel médical auprès des fournisseurs référencés.</td></tr>
            <tr><td><b>Acteur</b></td><td>Administrateur, Médecin</td></tr>
            <tr><td><b>Précondition</b></td><td>L'utilisateur est authentifié. Des fournisseurs et des articles sont enregistrés dans le système.</td></tr>
            <tr><td><b>Scénario nominal</b></td><td>
                1. L'utilisateur accède au module de commandes et clique sur « Nouvelle commande ».<br>
                2. Il sélectionne le fournisseur et ajoute les articles souhaités avec les quantités.<br>
                3. Le système calcule le montant total de la commande.<br>
                4. L'utilisateur valide et envoie la commande (statut : « Envoyée »).<br>
                5. Le fournisseur confirme la commande et met à jour le statut (Confirmée → Expédiée → Reçue).<br>
                6. À réception, le stock des articles commandés est automatiquement mis à jour.
            </td></tr>
            <tr><td><b>Scénario alternatif</b></td><td>4.1 – Le fournisseur n'est pas disponible : la commande reste en brouillon jusqu'à la sélection d'un fournisseur valide.</td></tr>
            <tr><td><b>Post-condition</b></td><td>La commande est créée et les niveaux de stock sont mis à jour à réception de la livraison.</td></tr>
        </tbody>
    </table>

    <h3>6.1.3&nbsp;&nbsp;Conception</h3>

    <h4>6.1.3.1&nbsp;&nbsp;Diagramme de séquence « Passer une commande »</h4>
    <div class="fig-box">[Diagramme de séquence – Passer une commande fournisseur<br>
    Utilisateur → SupplyOrderController : créerCommande(fournisseur, articles, token)<br>
    SupplyOrderController → SupplyOrderModel : générer numéro commande<br>
    SupplyOrderModel → SupplyOrderItemModel : sauvegarder lignes<br>
    SupplyOrderController → NotificationService : notifier fournisseur<br>
    [À réception] Fournisseur → SupplyOrderController : mettreAJourStatut(reçue)<br>
    SupplyOrderController → SupplyItemModel : mettreAJourStock(quantité)]</div>
    <p class="fig-cap"><b>Figure 6.2 :</b> Diagramme de séquence « Passer une commande fournisseur »</p>

    <h4>6.1.3.2&nbsp;&nbsp;Diagramme de classes du Sprint 4</h4>
    <div class="fig-box">[Diagramme de classes – Sprint 4<br>
    Supplier : -user_id, -company_name, -contact_name, -phone, -email, -address, -is_active<br>
    SupplyCategory : -name, -description<br>
    SupplyItem : -supplier_id, -category_id, -name, -sku, -unit_price, -stock_quantity, -min_stock_level<br>
    +isLowStock() : bool<br>
    SupplyOrder : -ordered_by, -supplier_id, -order_number, -status, -total_amount<br>
    statuts : draft | sent | confirmed | shipped | received | cancelled<br>
    SupplyOrderItem : -order_id, -item_id, -quantity, -unit_price, -subtotal]</div>
    <p class="fig-cap"><b>Figure 6.3 :</b> Diagramme de classes du Sprint 4</p>

    <h3>6.1.4&nbsp;&nbsp;Réalisation du Sprint 4</h3>

    <p>
        La figure 6.4 présente l'interface de gestion des articles en stock, affichant pour chaque
        article sa référence, son stock actuel, son seuil minimal et une indication visuelle de rupture
        imminente.
    </p>
    <div class="fig-box">[Capture d'écran – Liste des articles en stock<br>
    Tableau : Référence (SKU) | Nom | Catégorie | Fournisseur | Stock actuel | Seuil min | Prix | Statut stock<br>
    Indicateur rouge : Stock bas | Indicateur vert : Stock normal | Actions : Modifier | Commander]</div>
    <p class="fig-cap"><b>Figure 6.4 :</b> Interface de gestion des stocks médicaux</p>

    <p>
        La figure 6.5 illustre l'interface de création d'une commande fournisseur, permettant la
        sélection du fournisseur, l'ajout des articles souhaités et la définition des quantités.
    </p>
    <div class="fig-box">[Capture d'écran – Formulaire de commande fournisseur<br>
    Fournisseur (liste déroulante) | Date de commande | Date livraison prévue<br>
    Lignes : Article | Quantité | Prix unitaire | Sous-total<br>
    Total commande | Statut | Notes | Bouton : Envoyer la commande]</div>
    <p class="fig-cap"><b>Figure 6.5 :</b> Interface de création d'une commande fournisseur</p>

    <p>
        La figure 6.6 montre le tableau de bord fournisseur, permettant au fournisseur de visualiser
        les commandes reçues et de mettre à jour leur statut de livraison.
    </p>
    <div class="fig-box">[Capture d'écran – Espace fournisseur<br>
    Tableau des commandes reçues : Numéro | Date | Statut | Montant | Actions (Confirmer / Expédier / Livrer)]</div>
    <p class="fig-cap"><b>Figure 6.6 :</b> Tableau de bord fournisseur</p>

    <p class="hd-intro"><b>Conclusion</b></p>
    <p>
        Ce chapitre a présenté la conception et la réalisation du Sprint 4, couvrant la gestion complète
        de la chaîne d'approvisionnement du cabinet dentaire : gestion des stocks avec alertes, gestion
        des fournisseurs et processus de commande intégré. L'ensemble des quatre sprints constitue
        le système SmileCare dans sa version complète et fonctionnelle.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     CONCLUSION GÉNÉRALE
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Conclusion générale</div>

    <h1 class="chapter-title">Conclusion générale</h1>

    <p>
        Le présent rapport a été élaboré dans le cadre d'un projet de fin d'études en vue de l'obtention
        du diplôme national de licence en développement des systèmes d'information. Il décrit la conception
        et la réalisation de <b>SmileCare</b>, une application web complète dédiée à la gestion des cabinets
        dentaires.
    </p>
    <p>
        À travers ce projet, nous avons réussi à développer une plateforme qui répond aux besoins
        opérationnels réels d'un cabinet dentaire moderne. SmileCare centralise l'ensemble des processus
        administratifs et médicaux en un système unique et cohérent, offrant à chaque catégorie d'utilisateurs
        (médecins, secrétaires, patients et fournisseurs) des interfaces adaptées à leurs besoins spécifiques.
    </p>
    <p>
        Les principaux résultats atteints à l'issue de ce projet sont les suivants :
    </p>
    <ul>
        <li>Un système d'authentification sécurisé avec gestion des rôles et des permissions garantissant la confidentialité des données médicales.</li>
        <li>Un module complet de gestion des patients permettant la création et le suivi de dossiers médicaux détaillés (antécédents, allergies, historique de soins).</li>
        <li>Un système de planification des rendez-vous avec calendrier interactif, notifications automatiques et gestion de l'agenda des praticiens.</li>
        <li>Un module de facturation automatisée permettant la génération de factures PDF à partir des actes de soins enregistrés, avec suivi des paiements.</li>
        <li>Un système de génération d'ordonnances médicales et de bulletins CNAM imprimables, réduisant considérablement le temps de rédaction pour les praticiens.</li>
        <li>Un module complet de gestion des stocks et des commandes fournisseurs avec alertes de rupture de stock.</li>
    </ul>
    <p>
        Ce projet nous a permis de consolider nos compétences en développement web et d'acquérir une
        expérience concrète dans la conduite d'un projet de bout en bout selon la méthodologie SCRUM.
        Nous avons approfondi notre maîtrise du framework Laravel 13 et de ses écosystèmes (Livewire,
        Jetstream, Eloquent ORM), ainsi que des outils modernes du développement front-end (Tailwind CSS,
        Alpine.js, Inertia.js).
    </p>
    <p>
        Comme perspectives d'évolution, nous envisageons plusieurs améliorations futures pour SmileCare :
        l'intégration d'un module de téléconsultation, le développement d'une application mobile dédiée aux
        patients, la mise en place d'un système de rappels automatiques par SMS, et l'ajout de fonctionnalités
        d'analyse et de reporting avancés basés sur les données collectées.
    </p>
    <p>
        Ce projet de fin d'études représente une expérience profondément formatrice qui nous a préparés de
        manière solide pour nos futures carrières dans le domaine du développement des systèmes d'information.
    </p>
</div>
<div class="pb"></div>

<!-- ================================================================
     BIBLIOGRAPHIE
     ================================================================ -->
<div class="page">
    <div class="chap-hdr">Bibliographie</div>

    <h1 class="chapter-title">Bibliographie</h1>

    <p>[1] « Laravel Framework » [En ligne]. Disponible sur : https://laravel.com/docs</p>
    <p>[2] « Livewire – Full-stack framework for Laravel » [En ligne]. Disponible sur : https://livewire.laravel.com</p>
    <p>[3] « Tailwind CSS – A utility-first CSS framework » [En ligne]. Disponible sur : https://tailwindcss.com</p>
    <p>[4] « Alpine.js – Lightweight JavaScript framework » [En ligne]. Disponible sur : https://alpinejs.dev</p>
    <p>[5] « Inertia.js – Build single-page apps without building an API » [En ligne]. Disponible sur : https://inertiajs.com</p>
    <p>[6] « MySQL – Open-Source Relational Database » [En ligne]. Disponible sur : https://www.mysql.com</p>
    <p>[7] « Visual Studio Code » [En ligne]. Disponible sur : https://code.visualstudio.com</p>
    <p>[8] « Laravel Jetstream – Application Scaffolding » [En ligne]. Disponible sur : https://jetstream.laravel.com</p>
    <p>[9] « Scrum Guide – The Definitive Guide to Scrum » [En ligne]. Disponible sur : https://www.scrumguides.org</p>
    <p>[10] « Laragon – Portable, isolated, fast & powerful universal development environment » [En ligne]. Disponible sur : https://laragon.org</p>
    <p>[11] « DomPDF – HTML to PDF converter » [En ligne]. Disponible sur : https://github.com/barryvdh/laravel-dompdf</p>
    <p>[12] « Architecture MVC (Modèle-Vue-Contrôleur) » [En ligne]. Disponible sur : https://fr.wikipedia.org/wiki/Modèle-vue-contrôleur</p>
</div>

</body>
</html>
