<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Converter;

class RapportWordService
{
    private array $M;
    private array $BF;
    private array $BP;
    private array $CF;
    private array $CP;
    private array $FF;
    private array $FP;

    public function build(): PhpWord
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $w = new PhpWord();
        $w->setDefaultFontName('Arial');
        $w->setDefaultFontSize(11);

        $this->M  = ['marginTop' => Converter::cmToTwip(1.6), 'marginBottom' => Converter::cmToTwip(1.8), 'marginLeft' => Converter::cmToTwip(2.5), 'marginRight' => Converter::cmToTwip(2.5)];
        $this->BF = ['name' => 'Arial', 'size' => 11];
        $this->BP = ['alignment' => Jc::BOTH, 'spaceAfter' => 100];
        $this->CF = ['name' => 'Arial', 'size' => 10, 'bold' => true];
        $this->CP = ['alignment' => Jc::CENTER, 'spaceBefore' => 80, 'spaceAfter' => 40];
        $this->FF = ['name' => 'Arial', 'size' => 10, 'bold' => true];
        $this->FP = ['alignment' => Jc::CENTER, 'spaceBefore' => 40, 'spaceAfter' => 120];

        $w->addTitleStyle(1, ['name' => 'Arial', 'size' => 34, 'bold' => true], ['alignment' => Jc::CENTER, 'spaceBefore' => 400, 'spaceAfter' => 320]);
        $w->addTitleStyle(2, ['name' => 'Arial', 'size' => 13, 'bold' => true], ['spaceBefore' => 200, 'spaceAfter' => 70]);
        $w->addTitleStyle(3, ['name' => 'Arial', 'size' => 12, 'bold' => true], ['spaceBefore' => 130, 'spaceAfter' => 55]);
        $w->addTitleStyle(4, ['name' => 'Arial', 'size' => 11, 'bold' => true], ['spaceBefore' => 100, 'spaceAfter' => 40]);
        $w->addTableStyle('BT', ['borderColor' => 'BFDBFE', 'borderSize' => 6, 'cellMargin' => 70]);

        $this->intro($w);
        $this->ch1($w);
        $this->ch2($w);
        $this->ch3($w);
        $this->ch4($w);
        $this->ch5($w);
        $this->ch6($w);
        $this->conclusion($w);
        $this->biblio($w);

        return $w;
    }

    /* ─── section helper ─────────────────────────────────────── */

    private function sec(PhpWord $w, string $hdr): Section
    {
        $s = $w->addSection($this->M);
        $s->addHeader()->addText($hdr,
            ['name' => 'Arial', 'size' => 9, 'color' => '555555'],
            ['alignment' => Jc::RIGHT, 'borderBottomSize' => 6, 'borderBottomColor' => '999999', 'spaceAfter' => 200]);
        $s->addFooter()->addPreserveText('Page | {PAGE}', ['name' => 'Arial', 'size' => 9], ['alignment' => Jc::RIGHT]);
        return $s;
    }

    /* ─── text helpers ───────────────────────────────────────── */

    private function p(Section $s, string $t): void
    {
        $s->addText($t, $this->BF, $this->BP);
    }

    private function bold(Section $s, string $t): void
    {
        $s->addText($t, ['name' => 'Arial', 'size' => 12, 'bold' => true], ['spaceBefore' => 120, 'spaceAfter' => 60]);
    }

    private function inlineBold(Section $s, string $t): void
    {
        $s->addText($t, ['name' => 'Arial', 'size' => 11, 'bold' => true], ['spaceAfter' => 60]);
    }

    private function li(Section $s, string $label, string $text): void
    {
        $ip = ['indentation' => ['left' => 720, 'hanging' => 360], 'spaceAfter' => 50];
        $r = $s->addTextRun($ip);
        $r->addText('•  ', ['name' => 'Arial', 'size' => 10]);
        if ($label !== '') {
            $r->addText($label . ' ', ['name' => 'Arial', 'size' => 10, 'bold' => true]);
        }
        $r->addText($text, ['name' => 'Arial', 'size' => 10]);
    }

    private function ni(Section $s, int $n, string $label, string $text): void
    {
        $ip = ['indentation' => ['left' => 720, 'hanging' => 360], 'spaceAfter' => 50];
        $r = $s->addTextRun($ip);
        $r->addText($n . '.  ', ['name' => 'Arial', 'size' => 10]);
        if ($label !== '') {
            $r->addText($label . ' ', ['name' => 'Arial', 'size' => 10, 'bold' => true]);
        }
        $r->addText($text, ['name' => 'Arial', 'size' => 10]);
    }

    private function fig(Section $s, string $desc, string $cap): void
    {
        $t = $s->addTable(['borderColor' => 'CBD5E1', 'borderSize' => 6, 'cellMargin' => 150, 'width' => 100 * 50, 'unit' => 'pct']);
        $t->addRow(560)->addCell(null, ['bgColor' => 'F8FAFC'])
          ->addText($desc, ['name' => 'Arial', 'size' => 10, 'italic' => true, 'color' => '64748B'], ['alignment' => Jc::CENTER]);
        $s->addText($cap, $this->FF, $this->FP);
    }

    private function cap(Section $s, string $t): void
    {
        $s->addText($t, $this->CF, $this->CP);
    }

    /* ─── blue table helper ──────────────────────────────────── */
    // $headers: [['text' => '...', 'w' => 1500], ...]
    // $rows:    [[['text' => '...' | ['line1','line2'], 'label' => bool, 'w' => int], ...], ...]

    private function bt(Section $s, array $headers, array $rows): void
    {
        $t = $s->addTable('BT');
        $hr = $t->addRow(380);
        foreach ($headers as $h) {
            $hr->addCell($h['w'] ?? null, ['bgColor' => '1E40AF', 'valign' => 'center'])
               ->addText($h['text'], ['name' => 'Arial', 'size' => 10, 'bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        }
        $ri = 0;
        foreach ($rows as $rd) {
            $bg  = ($ri % 2 === 0) ? 'EFF6FF' : 'DBEAFE';
            $row = $t->addRow();
            foreach ($rd as $cd) {
                $lbl  = $cd['label'] ?? false;
                $vm   = $cd['vMerge'] ?? null;
                $cs   = $lbl ? ['bgColor' => '2563EB', 'valign' => 'center'] : ['bgColor' => $bg, 'valign' => 'top'];
                if ($vm) $cs['vMerge'] = $vm;
                $cell = $row->addCell($cd['w'] ?? null, $cs);
                if ($vm === 'continue') continue;
                $tf   = $lbl ? ['name' => 'Arial', 'size' => 10, 'bold' => true, 'color' => 'FFFFFF'] : ['name' => 'Arial', 'size' => 10];
                $tp   = $lbl ? ['alignment' => Jc::CENTER] : [];
                $lines = is_array($cd['text']) ? $cd['text'] : [$cd['text']];
                foreach ($lines as $line) $cell->addText($line, $tf, $tp);
            }
            $ri++;
        }
    }

    /* ─── use-case description table ────────────────────────── */

    private function ucTable(Section $s, string $cap, string $title, array $rows): void
    {
        $this->cap($s, $cap);
        $t = $s->addTable('BT');
        $hr = $t->addRow(380);
        $hr->addCell(2200, ['bgColor' => '1E40AF', 'valign' => 'center'])
           ->addText('Cas d\'utilisation', ['name' => 'Arial', 'size' => 10, 'bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $hr->addCell(6800, ['bgColor' => '1E40AF', 'valign' => 'center'])
           ->addText($title, ['name' => 'Arial', 'size' => 10, 'bold' => true, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);
        $ri = 0;
        foreach ($rows as [$label, $content]) {
            $bg  = ($ri % 2 === 0) ? 'EFF6FF' : 'DBEAFE';
            $row = $t->addRow();
            $row->addCell(2200, ['bgColor' => $bg, 'valign' => 'top'])
                ->addText($label, ['name' => 'Arial', 'size' => 10, 'bold' => true], []);
            $cell = $row->addCell(6800, ['bgColor' => $bg, 'valign' => 'top']);
            $lines = is_array($content) ? $content : [$content];
            foreach ($lines as $l) $cell->addText($l, ['name' => 'Arial', 'size' => 10], []);
            $ri++;
        }
    }

    /* ─── sprint backlog table ───────────────────────────────── */

    private function sprintBacklog(Section $s, string $cap, array $items): void
    {
        $this->cap($s, $cap);
        $this->bt($s,
            [['text' => 'Nom', 'w' => 2300], ['text' => 'Description', 'w' => 5200], ['text' => 'Estimation', 'w' => 1500]],
            array_map(fn($i) => [
                ['text' => $i[0], 'label' => true, 'w' => 2300],
                ['text' => $i[1], 'w' => 5200],
                ['text' => $i[2], 'w' => 1500],
            ], $items)
        );
    }

    /* ═══════════════════════════════════════════════════════════
       INTRODUCTION GÉNÉRALE
    ═══════════════════════════════════════════════════════════ */

    private function intro(PhpWord $w): void
    {
        $s = $this->sec($w, 'Introduction générale');
        $s->addTitle('Introduction générale', 1);

        $this->p($s, "Dans le contexte actuel où la transition numérique transforme profondément les pratiques professionnelles, le secteur de la santé dentaire se trouve face à un impératif de modernisation de ses outils de gestion. Les cabinets dentaires, qui accueillent quotidiennement un flux important de patients, doivent gérer simultanément la planification des rendez-vous, le suivi des dossiers médicaux, la facturation, la rédaction des ordonnances et la gestion des stocks de matériel médical. Cette multiplicité de tâches, souvent traitée de manière manuelle ou avec des outils disparates, constitue une source de pertes de temps, d'erreurs et d'inefficacités nuisant à la qualité des soins.");

        $r = $s->addTextRun($this->BP);
        $r->addText("Notre projet de fin d'études s'inscrit dans cette démarche de modernisation. Nous avons eu l'opportunité de concevoir et de développer ", $this->BF);
        $r->addText('SmileCare', ['name' => 'Arial', 'size' => 11, 'bold' => true]);
        $r->addText(", une application web complète dédiée à la gestion intégrée des cabinets dentaires. Cette plateforme centralise l'ensemble des processus administratifs et médicaux d'un cabinet, offrant ainsi aux praticiens et à leur personnel un outil unique, ergonomique et performant.", $this->BF);

        $this->p($s, "La structure de ce rapport est organisée en six chapitres principaux. Le premier chapitre présente le cadre général du projet, comprenant l'organisme d'accueil, la problématique identifiée et la solution proposée, ainsi que la méthodologie de développement retenue. Le deuxième chapitre expose la spécification fonctionnelle et technique à travers le Sprint 0. Les chapitres trois à six décrivent successivement la conception et la réalisation de chacun des quatre sprints : gestion des utilisateurs, gestion des patients et rendez-vous, gestion des traitements et documents médicaux, et gestion des stocks et fournisseurs. Le rapport se clôture par une conclusion générale récapitulant les apports du projet et les perspectives d'évolution envisagées.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 1
    ═══════════════════════════════════════════════════════════ */

    private function ch1(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 1 : Cadre général du projet');
        $s->addTitle('Chapitre 1 : Cadre général du projet', 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Le présent chapitre se concentre sur le contexte général de notre projet. Nous débutons par une présentation de l'organisme d'accueil, suivie de l'exposition de la problématique rencontrée et de la solution proposée. Nous concluons ce chapitre par la présentation de la méthodologie de développement adoptée pour la réalisation de SmileCare.");

        $s->addTitle("1.1  Présentation de l'organisme d'accueil", 2);
        $r = $s->addTextRun($this->BP);
        $r->addText("Ce projet a été développé en collaboration avec le ", $this->BF);
        $r->addText('Cabinet Dentaire « SourisBlanche »', ['name' => 'Arial', 'size' => 11, 'bold' => true]);
        $r->addText(", un établissement de soins dentaires situé à Tunis. Fondé en 2016, ce cabinet pluridisciplinaire dispose d'une équipe de trois chirurgiens-dentistes et de deux secrétaires médicales. Il accueille entre 40 et 60 patients par jour et propose une gamme complète de soins bucco-dentaires, notamment :", $this->BF);
        $this->li($s, 'Consultations et bilans dentaires :', 'Examens cliniques réguliers, détartrages, radiographies panoramiques et bilan de santé bucco-dentaire.');
        $this->li($s, 'Soins conservateurs :', 'Traitements des caries, dévitalisations, obturations esthétiques et scellements de sillons.');
        $this->li($s, 'Prothèses dentaires :', 'Couronnes céramiques, bridges, prothèses amovibles partielles et totales, et implantologie.');
        $this->li($s, 'Orthodontie :', 'Appareils dentaires fixes et amovibles pour adultes et enfants.');
        $this->li($s, 'Chirurgie buccale :', 'Extractions simples et complexes, greffes osseuses et traitements parodontaux.');

        $s->addTitle('1.2  Présentation du projet', 2);
        $s->addTitle('1.2.1  Cadre du projet', 3);
        $this->p($s, "Ce travail s'inscrit dans le cadre du projet de fin d'études pour l'obtention d'une licence en développement des systèmes d'information. Il a été réalisé au sein du Cabinet Dentaire SourisBlanche, dans l'objectif de répondre à un besoin réel de modernisation de la gestion administrative et médicale de l'établissement.");

        $s->addTitle('1.2.2  Problématique', 3);
        $this->p($s, "La gestion d'un cabinet dentaire implique un ensemble de tâches administratives et médicales complexes qui, lorsqu'elles sont effectuées manuellement ou via des outils non intégrés, génèrent d'importantes difficultés. Parmi les problèmes identifiés au sein du cabinet partenaire :");
        $this->li($s, 'Gestion des rendez-vous :', "La planification se faisait via un agenda papier, entraînant des risques de chevauchement, d'oublis et de difficultés dans la répartition des créneaux entre praticiens.");
        $this->li($s, 'Dossiers patients :', "Les informations médicales (antécédents, allergies, traitements) étaient dispersées dans des fichiers papier, rendant leur consultation difficile et risquée.");
        $this->li($s, 'Facturation :', "L'établissement des factures et le suivi des paiements nécessitaient une saisie manuelle fastidieuse et sujette aux erreurs de calcul.");
        $this->li($s, 'Gestion des stocks :', "Le suivi des consommables et du matériel médical était insuffisant, causant des ruptures de stock inopinées lors des soins.");
        $this->li($s, 'Documents médicaux :', "La génération des ordonnances et des bulletins CNAM était chronophage et non standardisée, ralentissant le flux des patients.");

        $s->addTitle('1.2.3  Solution proposée', 3);
        $r = $s->addTextRun($this->BP);
        $r->addText("Pour répondre à ces problèmes, nous proposons ", $this->BF);
        $r->addText('SmileCare', ['name' => 'Arial', 'size' => 11, 'bold' => true]);
        $r->addText(", une application web complète de gestion de cabinet dentaire. Il s'agit d'une plateforme centralisée accessible via navigateur, qui intègre l'ensemble des fonctionnalités nécessaires à la gestion quotidienne d'un cabinet dentaire :", $this->BF);
        $this->li($s, '', "Gestion des rendez-vous avec calendrier interactif et système de notifications automatiques.");
        $this->li($s, '', "Gestion complète des dossiers patients avec suivi de l'historique médical et des traitements.");
        $this->li($s, '', "Facturation automatisée avec génération de factures PDF et suivi des paiements.");
        $this->li($s, '', "Suivi des traitements dentaires par patient et par praticien (numérotation dentaire incluse).");
        $this->li($s, '', "Génération des ordonnances et bulletins CNAM en format imprimable.");
        $this->li($s, '', "Gestion des stocks médicaux avec alertes de rupture et commandes fournisseurs.");
        $this->li($s, '', "Tableaux de bord personnalisés par rôle d'utilisateur (médecin, secrétaire, patient, etc.).");

        $s->addPageBreak();

        $s->addTitle('1.3  Méthodologie de développement', 2);
        $this->p($s, "Le choix d'une méthodologie de gestion de projet est une étape déterminante pour la réussite d'un projet de développement logiciel. Elle offre une structure claire, optimise l'utilisation des ressources, minimise les risques et permet de bénéficier de l'expérience accumulée dans le domaine.");

        $s->addTitle('1.3.1  Comparatif des méthodologies existantes', 3);
        $this->p($s, "Dans le but de choisir la méthodologie la plus adaptée à notre projet, nous avons réalisé une étude comparative entre les principales approches de gestion de projets logiciels, présentée dans le tableau suivant :");
        $this->cap($s, 'Tableau 1.1 : Comparaison des méthodologies de développement');
        $this->bt($s,
            [['text' => 'Méthodologie', 'w' => 1300], ['text' => 'Description', 'w' => 2700], ['text' => 'Avantages', 'w' => 2300], ['text' => 'Inconvénients', 'w' => 2100]],
            [
                [
                    ['text' => 'RUP', 'label' => true, 'w' => 1300],
                    ['text' => "Méthodologie structurée basée sur une approche descendante, divisée en phases bien définies (inception, élaboration, construction, transition).", 'w' => 2700],
                    ['text' => ['• Approche itérative', '• Prise en compte des risques', '• Documentation complète'], 'w' => 2300],
                    ['text' => ['• Lourdeur de la documentation', '• Coût élevé', '• Planification rigide'], 'w' => 2100],
                ],
                [
                    ['text' => '2TUP', 'label' => true, 'w' => 1300],
                    ['text' => "Méthodologie itérative et incrémentale qui sépare le développement fonctionnel du développement technique, puis les fusionne en une branche d'assemblage.", 'w' => 2700],
                    ['text' => ['• Livraison rapide', '• Flexibilité technique', '• Séparation des préoccupations'], 'w' => 2300],
                    ['text' => ['• Faible documentation', '• Risques de dérive', '• Complexité de fusion'], 'w' => 2100],
                ],
                [
                    ['text' => 'SCRUM', 'label' => true, 'w' => 1300],
                    ['text' => "Méthodologie agile reposant sur des cycles itératifs courts (sprints) et des réunions régulières entre les membres de l'équipe pour assurer la progression et l'adaptation continue.", 'w' => 2700],
                    ['text' => ['• Flexibilité et adaptation', '• Approche itérative', '• Communication régulière', '• Livraison incrémentale'], 'w' => 2300],
                    ['text' => ['• Forte dépendance à la communication', '• Difficulté à maintenir la documentation'], 'w' => 2100],
                ],
            ]
        );

        $s->addTitle('1.3.2  Choix de la méthodologie SCRUM', 3);
        $this->p($s, "La méthode SCRUM a été retenue pour le développement de SmileCare, et ce pour plusieurs raisons fondamentales. Sa flexibilité permet d'adapter les priorités en cours de développement en fonction des retours du client. Son approche itérative garantit la livraison régulière de fonctionnalités opérationnelles, facilitant ainsi la validation progressive du produit. De plus, les cérémonies SCRUM (réunions quotidiennes, revues et rétrospectives de sprint) favorisent une communication efficace au sein de l'équipe et une détection précoce des problèmes.");

        $s->addTitle('1.3.3  Mise en pratique de la méthodologie SCRUM', 3);
        $this->p($s, "Le déroulement de SCRUM dans notre projet suit les étapes suivantes :");
        $this->ni($s, 1, 'Création du backlog produit :', "Définition de la liste priorisée de toutes les fonctionnalités à réaliser.");
        $this->ni($s, 2, 'Planification du sprint :', "Sélection des éléments du backlog à traiter lors du sprint suivant.");
        $this->ni($s, 3, 'Exécution du sprint :', "Développement des fonctionnalités sélectionnées sur une période de 2 à 3 semaines.");
        $this->ni($s, 4, 'Mêlée quotidienne :', "Courte réunion de synchronisation de l'équipe (15 minutes maximum).");
        $this->ni($s, 5, 'Revue de sprint :', "Démonstration des fonctionnalités développées au Product Owner.");
        $this->ni($s, 6, 'Rétrospective de sprint :', "Identification des axes d'amélioration pour le sprint suivant.");

        $this->fig($s, '[Figure 1.1 : Déroulement du processus Scrum]', 'Figure 1.1 : Déroulement du processus Scrum');

        $this->p($s, "Selon la méthodologie SCRUM, le tableau ci-dessous présente les acteurs impliqués dans notre projet :");
        $this->cap($s, 'Tableau 1.2 : Acteurs du projet selon la méthodologie SCRUM');
        $this->bt($s,
            [['text' => 'Rôle', 'w' => 3500], ['text' => 'Acteur', 'w' => 5500]],
            [
                [['text' => 'Product Owner', 'label' => true, 'w' => 3500], ['text' => 'Directeur du Cabinet Dentaire SourisBlanche', 'w' => 5500]],
                [['text' => 'Scrum Master',  'label' => true, 'w' => 3500], ['text' => 'Encadrant académique – ISET', 'w' => 5500]],
                [['text' => 'Scrum Team',    'label' => true, 'w' => 3500], ['text' => "L'équipe de développement (étudiants en licence DSI)", 'w' => 5500]],
            ]
        );

        $this->bold($s, 'Conclusion');
        $this->p($s, "Ce chapitre a permis de présenter le cadre général de notre projet, la problématique identifiée au sein du cabinet dentaire partenaire, la solution SmileCare que nous proposons, ainsi que la méthode SCRUM adoptée pour structurer notre développement. Le chapitre suivant sera consacré à la spécification fonctionnelle et technique complète de l'application.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 2 – SPRINT 0
    ═══════════════════════════════════════════════════════════ */

    private function ch2(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 2 : Sprint 0 – Spécification fonctionnelle et technique');
        $s->addTitle("Chapitre 2 : Sprint 0\n« Spécification fonctionnelle et technique »", 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Ce chapitre est consacré à la spécification détaillée de l'application SmileCare. Nous commençons par identifier les acteurs du système et leurs rôles, puis nous exposons les besoins fonctionnels et non fonctionnels, nous présentons le backlog produit et le diagramme de cas d'utilisation global, avant de décrire l'environnement technique et l'architecture générale de l'application.");

        $s->addTitle("2.1  Identification des acteurs", 2);
        $this->cap($s, 'Tableau 2.1 : Identification des acteurs');
        $this->bt($s,
            [['text' => 'Acteur', 'w' => 2000], ['text' => 'Rôle', 'w' => 7000]],
            [
                [['text' => 'Super Administrateur', 'w' => 2000], ['text' => "Dispose d'un accès total au système. Il gère les utilisateurs, les cabinets, les configurations globales et supervise l'ensemble des opérations de la plateforme.", 'w' => 7000]],
                [['text' => 'Administrateur', 'w' => 2000], ['text' => "Gère le personnel du cabinet, supervise les stocks et les commandes fournisseurs, consulte les statistiques et assure la gestion financière (factures, paiements).", 'w' => 7000]],
                [['text' => 'Médecin', 'w' => 2000], ['text' => "Consulte et gère son planning de rendez-vous, accède aux dossiers médicaux de ses patients, enregistre les actes réalisés, rédige les ordonnances et les bulletins CNAM.", 'w' => 7000]],
                [['text' => 'Secrétaire', 'w' => 2000], ['text' => "Planifie et confirme les rendez-vous, gère l'accueil des patients, établit les factures et assure le suivi administratif des dossiers patients.", 'w' => 7000]],
                [['text' => 'Patient', 'w' => 2000], ['text' => "Consulte son planning de rendez-vous, accède à son historique de traitements, visualise ses factures et ses documents médicaux (ordonnances, résultats).", 'w' => 7000]],
                [['text' => 'Fournisseur', 'w' => 2000], ['text' => "Reçoit les bons de commande émis par le cabinet, met à jour le statut des livraisons et communique les délais d'approvisionnement.", 'w' => 7000]],
            ]
        );

        $s->addTitle('2.2  Capture des besoins fonctionnels', 2);

        $this->inlineBold($s, 'Pour le Super Administrateur :');
        $this->li($s, 'Gérer les utilisateurs :', "Créer, modifier, désactiver et supprimer les comptes utilisateurs.");
        $this->li($s, 'Gérer les cabinets :', "Configurer les cabinets dentaires, affecter les médecins et secrétaires.");
        $this->li($s, 'Superviser le système :', "Accéder à toutes les données et fonctionnalités de l'application.");

        $this->inlineBold($s, "Pour l'Administrateur :");
        $this->li($s, 'Gérer le personnel :', "Administrer les comptes du personnel médical et administratif.");
        $this->li($s, 'Gérer les stocks :', "Superviser les niveaux de stock, valider les commandes fournisseurs.");
        $this->li($s, 'Consulter les statistiques :', "Accéder aux tableaux de bord financiers et opérationnels.");

        $this->inlineBold($s, 'Pour le Médecin :');
        $this->li($s, 'Gérer les rendez-vous :', "Consulter son planning, confirmer ou annuler les rendez-vous.");
        $this->li($s, 'Gérer les dossiers patients :', "Accéder à l'historique médical, aux allergies et aux antécédents.");
        $this->li($s, 'Enregistrer les traitements :', "Saisir les actes réalisés, le numéro de dent traité, les notes cliniques.");
        $this->li($s, 'Rédiger les ordonnances :', "Créer et imprimer des ordonnances médicamenteuses.");
        $this->li($s, 'Générer les bulletins CNAM :', "Produire les feuilles de soins pour la sécurité sociale.");
        $this->li($s, 'Commander des fournitures :', "Passer des commandes de matériel médical auprès des fournisseurs.");

        $this->inlineBold($s, 'Pour la Secrétaire :');
        $this->li($s, 'Gérer les rendez-vous :', "Planifier, confirmer, reporter ou annuler les rendez-vous des patients.");
        $this->li($s, 'Gérer les patients :', "Créer et mettre à jour les dossiers administratifs des patients.");
        $this->li($s, 'Établir les factures :', "Générer les factures à l'issue des consultations et traitements.");
        $this->li($s, 'Suivre les paiements :', "Enregistrer les règlements et gérer les impayés.");

        $this->inlineBold($s, 'Pour le Patient :');
        $this->li($s, 'Consulter ses rendez-vous :', "Visualiser le calendrier de ses prochains rendez-vous.");
        $this->li($s, 'Consulter son historique :', "Accéder à l'historique de ses traitements et actes réalisés.");
        $this->li($s, 'Consulter ses factures :', "Visualiser et télécharger ses factures en format PDF.");
        $this->li($s, 'Consulter ses documents :', "Accéder à ses ordonnances et bulletins CNAM.");

        $this->inlineBold($s, 'Pour le Fournisseur :');
        $this->li($s, 'Consulter les commandes :', "Visualiser les bons de commande reçus du cabinet.");
        $this->li($s, 'Mettre à jour le statut :', "Indiquer la confirmation, l'expédition et la réception des commandes.");

        $s->addPageBreak();

        $s->addTitle("2.2.1  Diagramme de cas d'utilisation global", 3);
        $this->p($s, "Le diagramme de cas d'utilisation global ci-dessous représente l'ensemble des interactions entre les acteurs et le système SmileCare.");
        $this->fig($s,
            "[Diagramme de cas d'utilisation global – SmileCare\nActeurs : Super Admin, Administrateur, Médecin, Secrétaire, Patient, Fournisseur\nCas d'utilisation : Gérer utilisateurs, Gérer rendez-vous, Gérer patients,\nGérer traitements, Facturer, Gérer stocks, Commander fournitures, Générer documents]",
            "Figure 2.1 : Diagramme de cas d'utilisation global de SmileCare"
        );

        $s->addTitle('2.2.2  Backlog produit', 3);
        $this->p($s, "Le backlog produit présente l'ensemble des user stories organisées par release et par sprint, classées selon leur priorité de réalisation.");
        $this->cap($s, 'Tableau 2.2 : Backlog produit de SmileCare');
        $this->bt($s,
            [['text' => 'Release', 'w' => 700], ['text' => 'User Story', 'w' => 2000], ['text' => 'Description', 'w' => 4300], ['text' => 'Priorité', 'w' => 1100], ['text' => 'Sprint', 'w' => 800]],
            [
                [['text' => '1', 'label' => true, 'w' => 700], ['text' => "S'authentifier", 'w' => 2000], ['text' => "En tant qu'utilisateur, je veux me connecter à la plateforme afin d'accéder aux fonctionnalités correspondant à mon rôle.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '1', 'w' => 800]],
                [['text' => '1', 'label' => true, 'w' => 700], ['text' => 'Gérer son profil', 'w' => 2000], ['text' => "En tant qu'utilisateur, je veux mettre à jour mes informations personnelles et changer mon mot de passe.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '1', 'w' => 800]],
                [['text' => '1', 'label' => true, 'w' => 700], ['text' => 'Gérer les utilisateurs', 'w' => 2000], ['text' => "En tant qu'administrateur, je veux créer, modifier, activer et désactiver les comptes utilisateurs du système.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '1', 'w' => 800]],
                [['text' => '1', 'label' => true, 'w' => 700], ['text' => 'Gérer les cabinets', 'w' => 2000], ['text' => "En tant que super administrateur, je veux configurer les cabinets et y affecter médecins et secrétaires.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '1', 'w' => 800]],
                [['text' => '2', 'label' => true, 'w' => 700], ['text' => 'Gérer les patients', 'w' => 2000], ['text' => "En tant que secrétaire ou médecin, je veux créer et gérer les dossiers patients (informations, antécédents, allergies).", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '2', 'w' => 800]],
                [['text' => '2', 'label' => true, 'w' => 700], ['text' => 'Gérer les rendez-vous', 'w' => 2000], ['text' => "En tant que secrétaire, je veux planifier, confirmer et annuler les rendez-vous des patients avec les médecins.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '2', 'w' => 800]],
                [['text' => '2', 'label' => true, 'w' => 700], ['text' => 'Consulter le calendrier', 'w' => 2000], ['text' => "En tant que médecin, je veux visualiser mon planning de rendez-vous sous forme de calendrier interactif.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '2', 'w' => 800]],
                [['text' => '3', 'label' => true, 'w' => 700], ['text' => 'Gérer les traitements', 'w' => 2000], ['text' => "En tant que médecin, je veux enregistrer les actes réalisés lors d'une consultation, avec le numéro de dent et les notes cliniques.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '3', 'w' => 800]],
                [['text' => '3', 'label' => true, 'w' => 700], ['text' => 'Facturer les soins', 'w' => 2000], ['text' => "En tant que secrétaire, je veux générer automatiquement une facture à partir des actes enregistrés lors d'un rendez-vous.", 'w' => 4300], ['text' => 'Élevée', 'w' => 1100], ['text' => '3', 'w' => 800]],
                [['text' => '3', 'label' => true, 'w' => 700], ['text' => 'Générer ordonnances', 'w' => 2000], ['text' => "En tant que médecin, je veux rédiger et imprimer des ordonnances médicamenteuses pour mes patients.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '3', 'w' => 800]],
                [['text' => '3', 'label' => true, 'w' => 700], ['text' => 'Générer bulletins CNAM', 'w' => 2000], ['text' => "En tant que médecin, je veux produire les bulletins de soins CNAM pour la prise en charge par la sécurité sociale.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '3', 'w' => 800]],
                [['text' => '4', 'label' => true, 'w' => 700], ['text' => 'Gérer les stocks', 'w' => 2000], ['text' => "En tant qu'administrateur, je veux gérer les articles en stock, consulter les niveaux et recevoir des alertes de rupture.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '4', 'w' => 800]],
                [['text' => '4', 'label' => true, 'w' => 700], ['text' => 'Gérer les fournisseurs', 'w' => 2000], ['text' => "En tant qu'administrateur, je veux gérer la liste des fournisseurs et leurs informations de contact.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '4', 'w' => 800]],
                [['text' => '4', 'label' => true, 'w' => 700], ['text' => 'Gérer les commandes', 'w' => 2000], ['text' => "En tant que médecin ou administrateur, je veux passer des commandes de matériel médical et suivre leur statut de livraison.", 'w' => 4300], ['text' => 'Moyenne', 'w' => 1100], ['text' => '4', 'w' => 800]],
            ]
        );

        $s->addPageBreak();

        $s->addTitle('2.3  Capture des besoins non fonctionnels', 2);
        $this->li($s, 'Sécurité :', "L'application doit garantir la confidentialité des données médicales. Elle met en œuvre un contrôle d'accès par rôle (RBAC) et le chiffrement des données sensibles.");
        $this->li($s, 'Performance :', "La plateforme doit répondre aux requêtes en moins de deux secondes, même en cas de charge importante.");
        $this->li($s, 'Disponibilité :', "L'application doit être accessible 24h/24 et 7j/7 avec un taux de disponibilité minimal de 99 %.");
        $this->li($s, 'Convivialité :', "L'interface utilisateur doit être intuitive, ergonomique et responsive, permettant une prise en main rapide.");
        $this->li($s, 'Fiabilité :', "Le système doit fonctionner sans interruption ni perte de données, avec des mécanismes de sauvegarde réguliers.");
        $this->li($s, 'Maintenabilité :', "L'architecture du code doit respecter les bonnes pratiques de développement (MVC, SOLID).");

        $s->addTitle('2.4  Capture des besoins techniques', 2);
        $s->addTitle("2.4.1  Environnement de développement", 3);
        $this->cap($s, 'Tableau 2.3 : Environnement de développement');
        $this->bt($s,
            [['text' => 'Technologie', 'w' => 2000], ['text' => 'Description', 'w' => 7000]],
            [
                [['text' => 'Laravel 13', 'w' => 2000], ['text' => "Framework PHP open-source basé sur l'architecture MVC. Il offre un ORM (Eloquent), un système de routage avancé et un moteur de templates (Blade).", 'w' => 7000]],
                [['text' => 'MySQL', 'w' => 2000], ['text' => "Système de gestion de bases de données relationnelles (SGBDR) assurant le stockage structuré et sécurisé de l'ensemble des données de l'application.", 'w' => 7000]],
                [['text' => 'Livewire 3', 'w' => 2000], ['text' => "Bibliothèque Laravel permettant de créer des composants dynamiques côté serveur sans écrire de JavaScript.", 'w' => 7000]],
                [['text' => 'Inertia.js', 'w' => 2000], ['text' => "Couche d'intégration permettant de créer des applications monopage (SPA) en combinant le backend Laravel et des vues côté client.", 'w' => 7000]],
                [['text' => 'Tailwind CSS 4', 'w' => 2000], ['text' => "Framework CSS utilitaire de nouvelle génération permettant de concevoir des interfaces modernes et responsives directement dans le balisage HTML.", 'w' => 7000]],
                [['text' => 'Alpine.js', 'w' => 2000], ['text' => "Framework JavaScript léger permettant d'ajouter des comportements interactifs côté client avec une syntaxe déclarative simple.", 'w' => 7000]],
                [['text' => 'Laravel Jetstream', 'w' => 2000], ['text' => "Scaffolding d'authentification fournissant la gestion des sessions, la vérification des e-mails et la gestion des profils utilisateurs.", 'w' => 7000]],
            ]
        );

        $s->addTitle("2.4.2  Environnement logiciel", 3);
        $this->cap($s, 'Tableau 2.4 : Environnement logiciel');
        $this->bt($s,
            [['text' => 'Outil', 'w' => 2000], ['text' => 'Description', 'w' => 7000]],
            [
                [['text' => 'Visual Studio Code', 'w' => 2000], ['text' => "Éditeur de code léger développé par Microsoft, offrant coloration syntaxique, auto-complétion, détection d'erreurs en temps réel et intégration Git.", 'w' => 7000]],
                [['text' => 'Laragon', 'w' => 2000], ['text' => "Environnement de développement web local pour Windows, intégrant PHP, MySQL, Apache/Nginx et Node.js.", 'w' => 7000]],
                [['text' => 'Git / GitHub', 'w' => 2000], ['text' => "Système de contrôle de version distribué utilisé pour gérer le code source, faciliter la collaboration et assurer la traçabilité des modifications.", 'w' => 7000]],
                [['text' => 'Composer', 'w' => 2000], ['text' => "Gestionnaire de dépendances PHP permettant d'installer et de gérer les bibliothèques tierces du projet.", 'w' => 7000]],
                [['text' => 'npm / Vite', 'w' => 2000], ['text' => "Gestionnaire de paquets JavaScript (npm) et outil de build moderne (Vite) pour la compilation des assets front-end.", 'w' => 7000]],
            ]
        );

        $s->addTitle("2.4.3  Architecture générale de l'application", 3);
        $r = $s->addTextRun($this->BP);
        $r->addText("SmileCare est développée selon l'architecture ", $this->BF);
        $r->addText('Modèle-Vue-Contrôleur (MVC)', ['name' => 'Arial', 'size' => 11, 'bold' => true]);
        $r->addText(", nativement supportée par le framework Laravel. Cette architecture sépare clairement les responsabilités du système en trois couches distinctes :", $this->BF);
        $this->li($s, 'Modèle (Model) :', "Représente la logique métier et les données. Les modèles Eloquent interagissent avec MySQL et encapsulent les règles de validation et les relations entre entités.");
        $this->li($s, 'Vue (View) :', "Représente la couche de présentation. Les vues Blade génèrent le HTML envoyé au navigateur, intégrant Tailwind CSS et Alpine.js.");
        $this->li($s, 'Contrôleur (Controller) :', "Fait le lien entre le Modèle et la Vue. Il reçoit les requêtes HTTP, applique la logique applicative et retourne la vue appropriée.");

        $this->fig($s,
            "[Figure 2.2 : Architecture MVC de SmileCare\nClient (Navigateur) → HTTP Request → Serveur Web (Laravel) → Controller → Model (Eloquent ORM) → MySQL\nMySQL → Model → Controller → View (Blade + Tailwind) → HTTP Response → Client]",
            'Figure 2.2 : Architecture physique de SmileCare'
        );

        $this->bold($s, 'Conclusion');
        $this->p($s, "Dans ce chapitre, nous avons défini les acteurs du système et leurs besoins fonctionnels, établi le backlog produit complet organisé en quatre sprints, précisé les exigences non fonctionnelles et présenté l'environnement technique retenu ainsi que l'architecture MVC de l'application. Le chapitre suivant sera consacré à l'étude et à la réalisation du premier sprint.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 3 – SPRINT 1
    ═══════════════════════════════════════════════════════════ */

    private function ch3(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 3 : Étude et réalisation du Sprint 1');
        $s->addTitle('Chapitre 3 : Étude et réalisation du Sprint 1', 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Ce chapitre décrit la mise en œuvre du premier sprint de SmileCare, consacré à l'authentification des utilisateurs et à la gestion des comptes. Chaque fonctionnalité du sprint est présentée selon une procédure structurée comprenant la spécification fonctionnelle, la conception et la réalisation.");

        $s->addTitle('3.1  Sprint 1 « Authentification et gestion des utilisateurs »', 2);
        $s->addTitle('3.1.1  Backlog du Sprint 1', 3);
        $this->sprintBacklog($s, 'Tableau 3.1 : Backlog du Sprint 1', [
            ["S'authentifier",    "En tant qu'utilisateur, je veux me connecter à la plateforme avec mon adresse e-mail et mon mot de passe afin d'accéder aux fonctionnalités correspondant à mon rôle.", '3 jours'],
            ['Gérer son profil',  "En tant qu'utilisateur, je veux consulter et mettre à jour mes informations personnelles et modifier mon mot de passe.", '2 jours'],
            ['Gérer les utilisateurs', "En tant qu'administrateur, je veux créer des comptes pour les médecins, secrétaires, patients et fournisseurs, et gérer leur accès (activation/désactivation).", '4 jours'],
            ['Gérer les cabinets', "En tant que super administrateur, je veux créer et configurer les cabinets dentaires en y associant un médecin référent et une secrétaire.", '3 jours'],
        ]);

        $s->addTitle('3.1.2  Spécification fonctionnelle', 3);
        $s->addTitle("3.1.2.1  Diagramme de cas d'utilisation du Sprint 1", 4);
        $this->p($s, "La figure 3.1 ci-dessous présente le diagramme de cas d'utilisation du Sprint 1, illustrant les interactions entre les différents acteurs et les fonctionnalités d'authentification et de gestion des utilisateurs.");
        $this->fig($s,
            "[Diagramme de cas d'utilisation – Sprint 1\nActeur : Utilisateur (S'authentifier, Gérer profil, Réinitialiser mot de passe)\nActeur : Administrateur (Gérer utilisateurs → inclut S'authentifier)\nActeur : Super Administrateur (Gérer cabinets → inclut S'authentifier)]",
            "Figure 3.1 : Diagramme de cas d'utilisation du Sprint 1"
        );

        $s->addTitle("3.1.2.2  Description textuelle des cas d'utilisation du Sprint 1", 4);
        $this->ucTable($s, "Tableau 3.2 : Description textuelle du cas d'utilisation « S'authentifier »", "S'authentifier", [
            ['Intérêts',           "Vérifier l'identité de l'utilisateur avant de lui accorder l'accès aux fonctionnalités de la plateforme correspondant à son rôle."],
            ['Acteur',             'Tous les utilisateurs du système'],
            ['Précondition',       "L'utilisateur dispose d'un compte actif avec une adresse e-mail et un mot de passe valides enregistrés dans le système."],
            ['Scénario nominal',   ["1. L'utilisateur accède à la page de connexion.", "2. Il saisit son adresse e-mail et son mot de passe.", "3. Le système vérifie les identifiants saisis.", "4. L'utilisateur est authentifié et redirigé vers son tableau de bord personnalisé."]],
            ['Scénario alternatif', ["3.1 – Les identifiants sont incorrects : un message d'erreur s'affiche.", "3.2 – Le compte est désactivé : l'accès est refusé avec un message explicatif.", "3.3 – L'utilisateur a oublié son mot de passe : il peut initier la procédure de réinitialisation."]],
            ['Post-condition',     "L'utilisateur est connecté et accède aux fonctionnalités correspondant à son rôle."],
        ]);

        $this->ucTable($s, "Tableau 3.3 : Description textuelle du cas d'utilisation « Gérer les utilisateurs »", 'Gérer les utilisateurs', [
            ['Intérêts',           "Permettre à l'administrateur de contrôler l'accès au système en gérant les comptes utilisateurs."],
            ['Acteur',             'Administrateur, Super Administrateur'],
            ['Précondition',       "L'administrateur est authentifié et dispose des droits de gestion des utilisateurs."],
            ['Scénario nominal',   ["1. L'administrateur accède à la liste des utilisateurs.", "2. Il choisit de créer un nouveau compte en renseignant les informations (nom, e-mail, rôle).", "3. Le système crée le compte et envoie les identifiants à l'utilisateur.", "4. L'administrateur peut modifier le rôle, activer ou désactiver un compte existant."]],
            ['Scénario alternatif', ["2.1 – L'adresse e-mail est déjà utilisée : un message d'erreur s'affiche indiquant le doublon."]],
            ['Post-condition',     'Le compte est créé ou mis à jour avec succès dans le système.'],
        ]);

        $s->addPageBreak();

        $s->addTitle('3.1.3  Conception', 3);
        $s->addTitle("3.1.3.1  Diagramme de séquence « S'authentifier »", 4);
        $this->fig($s,
            "[Diagramme de séquence – S'authentifier\nUtilisateur → LoginView : saisir email et mot de passe\nLoginView → AuthController : envoyer credentials\nAuthController → UserModel : rechercher(email, password)\nUserModel → AuthController : retourner résultat\n[Si valide] → Rediriger vers dashboard | [Si invalide] → Afficher message d'erreur]",
            "Figure 3.2 : Diagramme de séquence du cas d'utilisation « S'authentifier »"
        );

        $s->addTitle("3.1.3.2  Diagramme de séquence « Gérer les utilisateurs »", 4);
        $this->fig($s,
            "[Diagramme de séquence – Gérer les utilisateurs\nAdministrateur → UserListView : accéder à la liste\nAdministrateur → CreateUserForm : remplir formulaire\nCreateUserForm → UserController : créerUtilisateur(données, token)\nUserController → UserModel : valider et sauvegarder\nUserModel → UserController : retourner résultat\nUserController → UserListView : afficher confirmation]",
            "Figure 3.3 : Diagramme de séquence du cas d'utilisation « Gérer les utilisateurs »"
        );

        $s->addTitle('3.1.3.3  Diagramme de classes du Sprint 1', 4);
        $this->fig($s,
            "[Diagramme de classes – Sprint 1\nUser : -id, -name, -email, -password, -role, -phone, -is_active\n+sAuthentifier(), +mettreAJourProfil(), +reinitialiserMotDePasse()\nDoctorProfile : -user_id, -specialization, -license_number, -working_days\nPatientProfile : -user_id, -date_of_birth, -blood_type, -allergies, -cnam_id\nCabinet : -name, -doctor_id, -secretary_id, -is_active]",
            'Figure 3.4 : Diagramme de classes du Sprint 1'
        );

        $s->addTitle('3.1.4  Réalisation du Sprint 1', 3);
        $this->p($s, "La figure 3.5 illustre la page de connexion de SmileCare. L'utilisateur saisit son adresse e-mail et son mot de passe pour accéder à la plateforme.");
        $this->fig($s, "[Capture d'écran – Interface de connexion (login)\nChamps : Adresse e-mail, Mot de passe | Bouton : Se connecter | Lien : Mot de passe oublié ?]", 'Figure 3.5 : Interface de connexion à SmileCare');

        $this->p($s, "La figure 3.6 montre l'interface de gestion des utilisateurs accessible aux administrateurs, avec la liste complète des utilisateurs, leur rôle, leur statut et les actions disponibles.");
        $this->fig($s, "[Capture d'écran – Interface de gestion des utilisateurs\nTableau : Nom | Prénom | Email | Rôle | Statut | Actions (Modifier / Désactiver)]", "Figure 3.6 : Interface de gestion des utilisateurs");

        $this->p($s, "La figure 3.7 présente le tableau de bord personnalisé du médecin, qui affiche un résumé des rendez-vous du jour, les patients récents et les alertes de stock.");
        $this->fig($s, "[Capture d'écran – Tableau de bord Médecin\nWidgets : Rendez-vous du jour | Patients récents | Alertes stock | Statistiques mensuelles]", 'Figure 3.7 : Tableau de bord du médecin');

        $this->bold($s, 'Conclusion');
        $this->p($s, "Dans ce chapitre, nous avons présenté la conception et la réalisation du Sprint 1, couvrant l'authentification des utilisateurs, la gestion des profils, la gestion des comptes par l'administrateur et la configuration des cabinets. Le chapitre suivant sera consacré au Sprint 2, dédié à la gestion des patients et des rendez-vous.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 4 – SPRINT 2
    ═══════════════════════════════════════════════════════════ */

    private function ch4(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 4 : Étude et réalisation du Sprint 2');
        $s->addTitle('Chapitre 4 : Étude et réalisation du Sprint 2', 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Ce chapitre porte sur la conception et le développement du Sprint 2, qui couvre deux modules essentiels de SmileCare : la gestion des dossiers patients et la gestion des rendez-vous. Ces deux fonctionnalités constituent le cœur opérationnel du système et répondent directement aux besoins quotidiens des secrétaires et des médecins du cabinet.");

        $s->addTitle("4.1  Sprint 2 « Gestion des patients et des rendez-vous »", 2);
        $s->addTitle('4.1.1  Backlog du Sprint 2', 3);
        $this->sprintBacklog($s, 'Tableau 4.1 : Backlog du Sprint 2', [
            ['Gérer les patients',       "En tant que secrétaire ou médecin, je veux créer et gérer les dossiers patients incluant informations personnelles, antécédents médicaux, allergies et coordonnées d'urgence.", '5 jours'],
            ['Gérer les rendez-vous',    "En tant que secrétaire, je veux planifier, confirmer, reporter ou annuler les rendez-vous des patients et notifier automatiquement les médecins concernés.", '6 jours'],
            ['Consulter le calendrier',  "En tant que médecin, je veux visualiser mon planning sous forme de calendrier interactif (vue journalière, hebdomadaire, mensuelle) avec les détails de chaque rendez-vous.", '4 jours'],
            ['Gérer les notifications',  "En tant qu'utilisateur, je veux recevoir des notifications en temps réel pour les nouveaux rendez-vous, les modifications et les rappels importants.", '3 jours'],
        ]);

        $s->addTitle('4.1.2  Spécification fonctionnelle', 3);
        $s->addTitle("4.1.2.1  Diagramme de cas d'utilisation du Sprint 2", 4);
        $this->fig($s,
            "[Diagramme de cas d'utilisation – Sprint 2\nActeur Secrétaire : Gérer patients (Créer, Modifier, Afficher), Gérer rendez-vous (Planifier, Confirmer, Annuler)\nActeur Médecin : Consulter calendrier, Accéder dossier patient\nActeur Patient : Consulter ses rendez-vous]",
            "Figure 4.1 : Diagramme de cas d'utilisation du Sprint 2"
        );

        $s->addTitle("4.1.2.2  Description textuelle des cas d'utilisation du Sprint 2", 4);
        $this->ucTable($s, "Tableau 4.2 : Description textuelle du cas d'utilisation « Planifier un rendez-vous »", 'Planifier un rendez-vous', [
            ['Intérêts',           "Permettre à la secrétaire de réserver un créneau horaire pour un patient auprès d'un médecin disponible, avec attribution d'un cabinet."],
            ['Acteur',             'Secrétaire'],
            ['Précondition',       "La secrétaire est authentifiée. Le patient et le médecin existent dans le système. Le créneau horaire choisi est disponible."],
            ['Scénario nominal',   ["1. La secrétaire accède au formulaire de création de rendez-vous.", "2. Elle sélectionne le patient, le médecin, la date, l'heure et la durée.", "3. Elle précise le type de rendez-vous (consultation, soin, contrôle, urgence).", "4. Le système vérifie la disponibilité du médecin et du cabinet.", "5. Le rendez-vous est enregistré avec le statut « En attente » et une notification est envoyée."]],
            ['Scénario alternatif', ["4.1 – Le créneau est déjà occupé : le système affiche un message d'erreur et propose des créneaux alternatifs."]],
            ['Post-condition',     "Le rendez-vous est créé et visible dans le calendrier du médecin et dans l'espace patient."],
        ]);

        $this->ucTable($s, "Tableau 4.3 : Description textuelle du cas d'utilisation « Gérer les dossiers patients »", 'Gérer les dossiers patients', [
            ['Intérêts',           "Centraliser toutes les informations médicales et administratives d'un patient pour faciliter le suivi et la prise en charge."],
            ['Acteur',             'Secrétaire, Médecin'],
            ['Précondition',       "L'utilisateur est authentifié et dispose des droits d'accès aux dossiers patients."],
            ['Scénario nominal',   ["1. L'utilisateur accède à la liste des patients.", "2. Il crée un nouveau dossier patient en renseignant les informations personnelles, médicales et d'assurance.", "3. Le système valide et enregistre les données.", "4. Le dossier est accessible et modifiable à tout moment par le personnel autorisé."]],
            ['Scénario alternatif', ["2.1 – Un champ obligatoire est manquant : un message de validation s'affiche indiquant le champ concerné."]],
            ['Post-condition',     'Le dossier patient est créé ou mis à jour avec succès dans le système.'],
        ]);

        $s->addTitle('4.1.3  Conception', 3);
        $s->addTitle("4.1.3.1  Diagramme de séquence « Planifier un rendez-vous »", 4);
        $this->fig($s,
            "[Diagramme de séquence – Planifier un rendez-vous\nSecrétaire → AppointmentView : remplir formulaire\nAppointmentView → AppointmentController : créerRendezVous(données, token)\nAppointmentController → AppointmentModel : vérifierDisponibilité()\nAppointmentModel → Controller : disponible\nController → AppointmentModel : sauvegarder()\nController → NotificationService : notifier médecin et patient\nController → AppointmentView : confirmer création]",
            "Figure 4.2 : Diagramme de séquence « Planifier un rendez-vous »"
        );

        $s->addTitle('4.1.3.2  Diagramme de classes du Sprint 2', 4);
        $this->fig($s,
            "[Diagramme de classes – Sprint 2\nUser → PatientProfile : 1..1 | User → DoctorProfile : 1..1\nAppointment : -patient_id, -doctor_id, -cabinet_id, -appointment_date, -duration_minutes, -status, -type\nStatuts : pending | confirmed | in_progress | completed | cancelled | no_show\nTypes : checkup | consultation | procedure | follow_up | emergency\nPatientProfile : -date_of_birth, -blood_type, -allergies, -cnam_id, -insurance_provider\nCabinet : -name, -doctor_id, -secretary_id]",
            'Figure 4.3 : Diagramme de classes du Sprint 2'
        );

        $s->addPageBreak();

        $s->addTitle('4.1.4  Réalisation du Sprint 2', 3);
        $this->p($s, "La figure 4.4 présente la liste des patients avec les informations clés et les options d'action (consulter, modifier, accéder au dossier médical).");
        $this->fig($s, "[Capture d'écran – Liste des patients\nTableau : Nom | Prénom | Téléphone | Groupe sanguin | CNAM | Dernier RDV | Actions]", "Figure 4.4 : Interface de gestion des patients");

        $this->p($s, "La figure 4.5 illustre le formulaire de création de rendez-vous, permettant à la secrétaire de sélectionner le patient, le médecin, le type de rendez-vous, la date et l'heure souhaitées.");
        $this->fig($s, "[Capture d'écran – Formulaire de création de rendez-vous\nChamps : Patient | Médecin | Type | Date et heure | Durée | Motif | Notes | Cabinet]", "Figure 4.5 : Interface de création d'un rendez-vous");

        $this->p($s, "La figure 4.6 montre le calendrier interactif du médecin affichant ses rendez-vous planifiés, avec des codes couleur selon le type et le statut de chaque rendez-vous.");
        $this->fig($s, "[Capture d'écran – Calendrier des rendez-vous\nVue hebdomadaire : Bleu = Consultation | Vert = Contrôle | Orange = Soin | Rouge = Urgence]", "Figure 4.6 : Calendrier des rendez-vous du médecin");

        $this->p($s, "La figure 4.7 présente la fiche détaillée d'un patient, regroupant ses informations médicales, son historique de rendez-vous et de traitements, ainsi que ses documents médicaux.");
        $this->fig($s, "[Capture d'écran – Fiche patient détaillée\nSections : Informations personnelles | Antécédents médicaux | Allergies | Historique des rendez-vous | Traitements réalisés | Factures | Documents médicaux]", "Figure 4.7 : Fiche détaillée d'un patient");

        $this->bold($s, 'Conclusion');
        $this->p($s, "Ce chapitre a décrit la conception et la réalisation du Sprint 2, couvrant la gestion complète des dossiers patients et le système de planification des rendez-vous avec calendrier interactif. Le Sprint 3, présenté dans le chapitre suivant, sera consacré à la gestion des traitements, de la facturation et des documents médicaux.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 5 – SPRINT 3
    ═══════════════════════════════════════════════════════════ */

    private function ch5(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 5 : Étude et réalisation du Sprint 3');
        $s->addTitle('Chapitre 5 : Étude et réalisation du Sprint 3', 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Ce chapitre présente le Sprint 3 de SmileCare, dédié aux fonctionnalités médicales et financières de l'application. Il couvre l'enregistrement des actes de soins, la génération automatique des factures, la rédaction des ordonnances médicales et la production des bulletins CNAM pour la prise en charge par la caisse nationale d'assurance maladie.");

        $s->addTitle("5.1  Sprint 3 « Traitements, Factures et Documents médicaux »", 2);
        $s->addTitle('5.1.1  Backlog du Sprint 3', 3);
        $this->sprintBacklog($s, 'Tableau 5.1 : Backlog du Sprint 3', [
            ['Gérer les traitements',              "En tant que médecin, je veux enregistrer les actes de soins réalisés lors d'une consultation en précisant le type, le numéro de dent, le coût et les notes cliniques.", '5 jours'],
            ['Gérer les catégories de traitements', "En tant qu'administrateur, je veux créer et gérer les catégories de traitements dentaires et y associer des actes avec leurs tarifs.", '3 jours'],
            ['Facturer les soins',                 "En tant que secrétaire, je veux générer automatiquement une facture à partir des actes enregistrés, appliquer des remises, calculer la TVA et imprimer la facture en PDF.", '5 jours'],
            ['Générer les ordonnances',            "En tant que médecin, je veux rédiger une ordonnance médicamenteuse pour mon patient, incluant les médicaments, les dosages et la posologie, et l'imprimer.", '3 jours'],
            ['Générer les bulletins CNAM',         "En tant que médecin, je veux produire un bulletin de soins CNAM reprenant les actes dentaires réalisés et les prothèses posées, pour la prise en charge sociale du patient.", '4 jours'],
        ]);

        $s->addTitle('5.1.2  Spécification fonctionnelle', 3);
        $s->addTitle("5.1.2.1  Diagramme de cas d'utilisation du Sprint 3", 4);
        $this->fig($s,
            "[Diagramme de cas d'utilisation – Sprint 3\nActeur Médecin : Enregistrer traitement, Rédiger ordonnance, Générer bulletin CNAM\nActeur Secrétaire : Créer facture, Marquer facture payée, Imprimer facture\nActeur Patient : Consulter ses traitements, Consulter ses factures\nRelations : Créer facture inclut Enregistrer traitement | Générer CNAM étend Enregistrer traitement]",
            "Figure 5.1 : Diagramme de cas d'utilisation du Sprint 3"
        );

        $s->addTitle("5.1.2.2  Description textuelle des cas d'utilisation du Sprint 3", 4);
        $this->ucTable($s, "Tableau 5.2 : Description textuelle du cas d'utilisation « Facturer les soins »", 'Facturer les soins', [
            ['Intérêts',           "Générer automatiquement une facture à partir des actes de soins enregistrés lors d'un rendez-vous, avec gestion des remises, taxes et modes de paiement."],
            ['Acteur',             'Secrétaire, Médecin'],
            ['Précondition',       "Le rendez-vous est terminé et les actes de soins correspondants ont été enregistrés par le médecin."],
            ['Scénario nominal',   ["1. La secrétaire accède au rendez-vous terminé et sélectionne « Créer facture ».", "2. Le système pré-remplit la facture avec les actes enregistrés et leurs tarifs.", "3. La secrétaire vérifie les lignes, applique éventuellement une remise.", "4. Le système calcule le sous-total, la TVA et le montant total.", "5. La facture est enregistrée avec le numéro automatique (INV-XXXXX) et le statut « Émise ».", "6. La secrétaire peut imprimer ou télécharger la facture en PDF."]],
            ['Scénario alternatif', ["2.1 – Aucun acte enregistré : le système avertit que la facture ne peut être générée sans actes associés."]],
            ['Post-condition',     "La facture est créée, visible dans l'espace patient et dans la liste des factures du cabinet."],
        ]);

        $s->addTitle('5.1.3  Conception', 3);
        $s->addTitle("5.1.3.1  Diagramme de séquence « Créer une facture »", 4);
        $this->fig($s,
            "[Diagramme de séquence – Créer une facture\nSecrétaire → InvoiceController : créerFacture(appointmentId, token)\nInvoiceController → TreatmentRecordModel : getTreatmentsByAppointment()\nTreatmentRecordModel → InvoiceController : retourner actes\nInvoiceController → InvoiceModel : sauvegarder(invoice_number, sous-total, TVA, total)\nInvoiceModel → InvoiceItemModel : sauvegarder lignes\nInvoiceController → PDFService : générer PDF\nInvoiceController → InvoiceView : afficher facture]",
            "Figure 5.2 : Diagramme de séquence « Créer une facture »"
        );

        $s->addTitle('5.1.3.2  Diagramme de classes du Sprint 3', 4);
        $this->fig($s,
            "[Diagramme de classes – Sprint 3\nInvoice : -invoice_number (INV-#####), -patient_id, -appointment_id\n-subtotal, -discount, -tax, -total, -status (draft|issued|paid|overdue|cancelled), -due_date\nInvoiceItem : -invoice_id, -treatment_id, -description, -quantity, -unit_price, -subtotal\nTreatment : -category_id, -name, -duration_minutes, -price\nTreatmentRecord : -patient_id, -doctor_id, -appointment_id, -tooth_number, -status, -cost\nOrdonnance : -appointment_id, -items[] (médicaments), -notes\nCnamBulletin : -appointment_id, -dental_acts[], -prostheses[]]",
            'Figure 5.3 : Diagramme de classes du Sprint 3'
        );

        $s->addPageBreak();

        $s->addTitle('5.1.4  Réalisation du Sprint 3', 3);
        $this->p($s, "La figure 5.4 illustre l'interface d'enregistrement des traitements, permettant au médecin de saisir les actes réalisés lors de la consultation avec la sélection du numéro de dent traité.");
        $this->fig($s, "[Capture d'écran – Enregistrement d'un traitement\nChamps : Patient | Médecin | Rendez-vous | Type (catégorie → acte) | Numéro de dent | Statut | Date | Coût | Notes cliniques]", "Figure 5.4 : Interface d'enregistrement d'un traitement");

        $this->p($s, "La figure 5.5 présente la facture générée automatiquement à partir des actes de soins, affichant le numéro unique (INV-XXXXX), le détail des lignes, les remises, la TVA et le total.");
        $this->fig($s, "[Capture d'écran – Facture générée\nEn-tête : Logo cabinet | Numéro (INV-00001) | Date d'émission | Date d'échéance\nTableau : Description | Quantité | Prix unitaire | Sous-total\nPied : Sous-total | Remise | TVA (19%) | Total TTC | Statut de paiement]", "Figure 5.5 : Aperçu d'une facture générée par SmileCare");

        $this->p($s, "La figure 5.6 montre l'interface de génération d'une ordonnance médicale, permettant au médecin de saisir les médicaments prescrits avec leurs dosages et la durée du traitement.");
        $this->fig($s, "[Capture d'écran – Interface de création d'ordonnance\nLignes médicaments : Nom | Dosage | Posologie | Durée\nNotes complémentaires | Boutons : Ajouter médicament | Imprimer ordonnance]", "Figure 5.6 : Interface de création d'ordonnance");

        $this->p($s, "La figure 5.7 illustre le formulaire de saisie du bulletin CNAM permettant au médecin de documenter les actes dentaires réalisés pour la prise en charge par l'assurance maladie.");
        $this->fig($s, "[Capture d'écran – Bulletin CNAM\nEn-tête : Informations patient | Numéro CNAM | Type d'assuré\nTableau actes dentaires | Section prothèses | Signature médecin | Date de soins]", "Figure 5.7 : Interface de génération du bulletin CNAM");

        $this->bold($s, 'Conclusion');
        $this->p($s, "Ce chapitre a présenté la conception et la réalisation du Sprint 3, couvrant l'enregistrement des actes de soins, la facturation automatisée, la génération des ordonnances et la production des bulletins CNAM. Le Sprint 4, présenté dans le chapitre suivant, sera consacré à la gestion des stocks et des fournisseurs.");
    }

    /* ═══════════════════════════════════════════════════════════
       CHAPITRE 6 – SPRINT 4
    ═══════════════════════════════════════════════════════════ */

    private function ch6(PhpWord $w): void
    {
        $s = $this->sec($w, 'Chapitre 6 : Étude et réalisation du Sprint 4');
        $s->addTitle('Chapitre 6 : Étude et réalisation du Sprint 4', 1);

        $this->bold($s, 'Introduction');
        $this->p($s, "Ce dernier chapitre de développement est consacré au Sprint 4, qui couvre la gestion de la chaîne d'approvisionnement du cabinet dentaire. Il comprend la gestion des articles en stock, le suivi des niveaux avec alertes de rupture, la gestion des fournisseurs et le processus complet de commande de matériel médical.");

        $s->addTitle("6.1  Sprint 4 « Gestion des stocks et des fournisseurs »", 2);
        $s->addTitle('6.1.1  Backlog du Sprint 4', 3);
        $this->sprintBacklog($s, 'Tableau 6.1 : Backlog du Sprint 4', [
            ['Gérer les articles en stock',    "En tant qu'administrateur, je veux créer et gérer la liste des articles médicaux avec leur référence, leur prix unitaire, leur stock actuel et leur seuil minimal d'alerte.", '4 jours'],
            ['Gérer les catégories de stock',  "En tant qu'administrateur, je veux organiser les articles en catégories (instruments, consommables, prothèses, médicaments) pour faciliter la recherche et le suivi.", '2 jours'],
            ['Gérer les fournisseurs',         "En tant qu'administrateur, je veux créer et gérer la liste des fournisseurs avec leurs coordonnées, afin de les associer aux articles qu'ils approvisionnent.", '3 jours'],
            ['Gérer les commandes fournisseurs', "En tant que médecin ou administrateur, je veux créer des bons de commande, les envoyer aux fournisseurs et suivre leur statut (brouillon, envoyée, confirmée, expédiée, reçue).", '5 jours'],
            ['Alertes de stock',               "En tant qu'administrateur ou médecin, je veux recevoir des alertes automatiques lorsque le niveau d'un article atteint son seuil minimal, afin de lancer une commande à temps.", '2 jours'],
        ]);

        $s->addTitle('6.1.2  Spécification fonctionnelle', 3);
        $s->addTitle("6.1.2.1  Diagramme de cas d'utilisation du Sprint 4", 4);
        $this->fig($s,
            "[Diagramme de cas d'utilisation – Sprint 4\nActeur Administrateur : Gérer articles, Gérer catégories, Gérer fournisseurs, Passer commande, Recevoir alerte stock\nActeur Médecin : Passer commande, Consulter stock, Recevoir alerte\nActeur Fournisseur : Consulter commandes reçues, Mettre à jour statut livraison]",
            "Figure 6.1 : Diagramme de cas d'utilisation du Sprint 4"
        );

        $s->addTitle("6.1.2.2  Description textuelle des cas d'utilisation du Sprint 4", 4);
        $this->ucTable($s, "Tableau 6.2 : Description textuelle du cas d'utilisation « Gérer les commandes fournisseurs »", 'Gérer les commandes fournisseurs', [
            ['Intérêts',           "Permettre au médecin ou à l'administrateur de créer et de suivre les bons de commande de matériel médical auprès des fournisseurs référencés."],
            ['Acteur',             'Administrateur, Médecin'],
            ['Précondition',       "L'utilisateur est authentifié. Des fournisseurs et des articles sont enregistrés dans le système."],
            ['Scénario nominal',   ["1. L'utilisateur accède au module de commandes et clique sur « Nouvelle commande ».", "2. Il sélectionne le fournisseur et ajoute les articles souhaités avec les quantités.", "3. Le système calcule le montant total de la commande.", "4. L'utilisateur valide et envoie la commande (statut : « Envoyée »).", "5. Le fournisseur confirme la commande et met à jour le statut (Confirmée → Expédiée → Reçue).", "6. À réception, le stock des articles commandés est automatiquement mis à jour."]],
            ['Scénario alternatif', ["4.1 – Le fournisseur n'est pas disponible : la commande reste en brouillon jusqu'à la sélection d'un fournisseur valide."]],
            ['Post-condition',     'La commande est créée et les niveaux de stock sont mis à jour à réception de la livraison.'],
        ]);

        $s->addTitle('6.1.3  Conception', 3);
        $s->addTitle("6.1.3.1  Diagramme de séquence « Passer une commande »", 4);
        $this->fig($s,
            "[Diagramme de séquence – Passer une commande fournisseur\nUtilisateur → SupplyOrderController : créerCommande(fournisseur, articles, token)\nSupplyOrderController → SupplyOrderModel : générer numéro commande\nSupplyOrderModel → SupplyOrderItemModel : sauvegarder lignes\nSupplyOrderController → NotificationService : notifier fournisseur\n[À réception] Fournisseur → SupplyOrderController : mettreAJourStatut(reçue)\nSupplyOrderController → SupplyItemModel : mettreAJourStock(quantité)]",
            "Figure 6.2 : Diagramme de séquence « Passer une commande fournisseur »"
        );

        $s->addTitle('6.1.3.2  Diagramme de classes du Sprint 4', 4);
        $this->fig($s,
            "[Diagramme de classes – Sprint 4\nSupplier : -user_id, -company_name, -contact_name, -phone, -email, -address, -is_active\nSupplyCategory : -name, -description\nSupplyItem : -supplier_id, -category_id, -name, -sku, -unit_price, -stock_quantity, -min_stock_level\n+isLowStock() : bool\nSupplyOrder : -ordered_by, -supplier_id, -order_number, -status, -total_amount\nStatuts : draft | sent | confirmed | shipped | received | cancelled\nSupplyOrderItem : -order_id, -item_id, -quantity, -unit_price, -subtotal]",
            'Figure 6.3 : Diagramme de classes du Sprint 4'
        );

        $s->addTitle('6.1.4  Réalisation du Sprint 4', 3);
        $this->p($s, "La figure 6.4 présente l'interface de gestion des articles en stock, affichant pour chaque article sa référence, son stock actuel, son seuil minimal et une indication visuelle de rupture imminente.");
        $this->fig($s, "[Capture d'écran – Liste des articles en stock\nTableau : SKU | Nom | Catégorie | Fournisseur | Stock actuel | Seuil min | Prix | Statut stock\nIndicateur rouge : Stock bas | Indicateur vert : Stock normal | Actions : Modifier | Commander]", "Figure 6.4 : Interface de gestion des stocks médicaux");

        $this->p($s, "La figure 6.5 illustre l'interface de création d'une commande fournisseur, permettant la sélection du fournisseur, l'ajout des articles souhaités et la définition des quantités.");
        $this->fig($s, "[Capture d'écran – Formulaire de commande fournisseur\nFournisseur | Date de commande | Date livraison prévue\nLignes : Article | Quantité | Prix unitaire | Sous-total\nTotal commande | Statut | Notes | Bouton : Envoyer la commande]", "Figure 6.5 : Interface de création d'une commande fournisseur");

        $this->p($s, "La figure 6.6 montre le tableau de bord fournisseur, permettant au fournisseur de visualiser les commandes reçues et de mettre à jour leur statut de livraison.");
        $this->fig($s, "[Capture d'écran – Espace fournisseur\nTableau des commandes reçues : Numéro | Date | Statut | Montant | Actions (Confirmer / Expédier / Livrer)]", "Figure 6.6 : Tableau de bord fournisseur");

        $this->bold($s, 'Conclusion');
        $this->p($s, "Ce chapitre a présenté la conception et la réalisation du Sprint 4, couvrant la gestion complète de la chaîne d'approvisionnement du cabinet dentaire : gestion des stocks avec alertes, gestion des fournisseurs et processus de commande intégré. L'ensemble des quatre sprints constitue le système SmileCare dans sa version complète et fonctionnelle.");
    }

    /* ═══════════════════════════════════════════════════════════
       CONCLUSION GÉNÉRALE
    ═══════════════════════════════════════════════════════════ */

    private function conclusion(PhpWord $w): void
    {
        $s = $this->sec($w, 'Conclusion générale');
        $s->addTitle('Conclusion générale', 1);

        $this->p($s, "Le présent rapport a été élaboré dans le cadre d'un projet de fin d'études en vue de l'obtention du diplôme national de licence en développement des systèmes d'information. Il décrit la conception et la réalisation de SmileCare, une application web complète dédiée à la gestion des cabinets dentaires.");
        $this->p($s, "À travers ce projet, nous avons réussi à développer une plateforme qui répond aux besoins opérationnels réels d'un cabinet dentaire moderne. SmileCare centralise l'ensemble des processus administratifs et médicaux en un système unique et cohérent, offrant à chaque catégorie d'utilisateurs des interfaces adaptées à leurs besoins spécifiques.");
        $this->p($s, "Les principaux résultats atteints à l'issue de ce projet sont les suivants :");
        $this->li($s, '', "Un système d'authentification sécurisé avec gestion des rôles et des permissions garantissant la confidentialité des données médicales.");
        $this->li($s, '', "Un module complet de gestion des patients permettant la création et le suivi de dossiers médicaux détaillés (antécédents, allergies, historique de soins).");
        $this->li($s, '', "Un système de planification des rendez-vous avec calendrier interactif, notifications automatiques et gestion de l'agenda des praticiens.");
        $this->li($s, '', "Un module de facturation automatisée permettant la génération de factures PDF à partir des actes de soins enregistrés, avec suivi des paiements.");
        $this->li($s, '', "Un système de génération d'ordonnances médicales et de bulletins CNAM imprimables, réduisant considérablement le temps de rédaction pour les praticiens.");
        $this->li($s, '', "Un module complet de gestion des stocks et des commandes fournisseurs avec alertes de rupture de stock.");
        $this->p($s, "Ce projet nous a permis de consolider nos compétences en développement web et d'acquérir une expérience concrète dans la conduite d'un projet de bout en bout selon la méthodologie SCRUM. Nous avons approfondi notre maîtrise du framework Laravel 13 et de ses écosystèmes (Livewire, Jetstream, Eloquent ORM), ainsi que des outils modernes du développement front-end (Tailwind CSS, Alpine.js, Inertia.js).");
        $this->p($s, "Comme perspectives d'évolution, nous envisageons plusieurs améliorations futures pour SmileCare : l'intégration d'un module de téléconsultation, le développement d'une application mobile dédiée aux patients, la mise en place d'un système de rappels automatiques par SMS, et l'ajout de fonctionnalités d'analyse et de reporting avancés basés sur les données collectées.");
        $this->p($s, "Ce projet de fin d'études représente une expérience profondément formatrice qui nous a préparés de manière solide pour nos futures carrières dans le domaine du développement des systèmes d'information.");
    }

    /* ═══════════════════════════════════════════════════════════
       BIBLIOGRAPHIE
    ═══════════════════════════════════════════════════════════ */

    private function biblio(PhpWord $w): void
    {
        $s = $this->sec($w, 'Bibliographie');
        $s->addTitle('Bibliographie', 1);

        $refs = [
            '[1] « Laravel Framework » [En ligne]. Disponible sur : https://laravel.com/docs',
            '[2] « Livewire – Full-stack framework for Laravel » [En ligne]. Disponible sur : https://livewire.laravel.com',
            '[3] « Tailwind CSS – A utility-first CSS framework » [En ligne]. Disponible sur : https://tailwindcss.com',
            '[4] « Alpine.js – Lightweight JavaScript framework » [En ligne]. Disponible sur : https://alpinejs.dev',
            '[5] « Inertia.js – Build single-page apps without building an API » [En ligne]. Disponible sur : https://inertiajs.com',
            '[6] « MySQL – Open-Source Relational Database » [En ligne]. Disponible sur : https://www.mysql.com',
            '[7] « Visual Studio Code » [En ligne]. Disponible sur : https://code.visualstudio.com',
            '[8] « Laravel Jetstream – Application Scaffolding » [En ligne]. Disponible sur : https://jetstream.laravel.com',
            '[9] « Scrum Guide – The Definitive Guide to Scrum » [En ligne]. Disponible sur : https://www.scrumguides.org',
            '[10] « Laragon – Portable, isolated, fast & powerful universal development environment » [En ligne]. Disponible sur : https://laragon.org',
            '[11] « DomPDF – HTML to PDF converter » [En ligne]. Disponible sur : https://github.com/barryvdh/laravel-dompdf',
            '[12] « Architecture MVC (Modèle-Vue-Contrôleur) » [En ligne]. Disponible sur : https://fr.wikipedia.org/wiki/Mod%C3%A8le-vue-contr%C3%B4leur',
        ];

        foreach ($refs as $ref) {
            $s->addText($ref, ['name' => 'Arial', 'size' => 10], ['spaceAfter' => 80]);
        }
    }
}
