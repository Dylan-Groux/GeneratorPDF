<?php

    namespace App\Controller\Generator;


    use Symfony\Component\HttpFoundation\Response;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use mikehaertl\pdftk\Pdf;

    use App\Entity\User;

    class PdfController extends AbstractController
    {
        #[Route ('/pdf', name: 'app_pdf')]
        public function generatePdf(EntityManagerInterface $em) : Response
        {
            //On donne le chemin du PDF
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form.pdf';

            // Vérifie si le fichier existe
            if (!file_exists($pdfPath)) {
                return new JsonResponse(['error' => 'Le fichier PDF est introuvable !'], 404);
            }

            //si l'id du formulaire correspond alors remplis sa valeur sinon null (par défault)
            $fieldValues = [
                /* <----! DGFiP N°2033-A-SD 2025 !----> */
                'designation_entreprise' => "",
                'adresse_entreprise' => "",
                'siret' => "",
                'duree_exercice_mois' => '',
                'duree_exercice' => '',
                'duree_exercice_n_clos' => '',

                /* <----! ACTIF !----> */
                  /* <--! BRUT !--> */
                '010_fondsCommercial_brut' => '',
                '014_autres_brut' => '',
                '028_immobiliCorporelles_brut' => '',
                '040_immobiliFinanciere_brut' => '',
                '044_total1_brut' => '',
                '050_stock_matierePremiere_brut' => '',
                '060_stock_marchandise_brut ' => '',
                '064_avances_acompte_brut' => '',
                '068_creances_clientsEtCompte_brut'=> '',
                '072_creances_autres_brut' => '',
                '080_valeursMobilieres_brut' => '',
                '084_disponibilites_brut' => '',
                '092_chargesConstatees_brut' => '',
                '096_total2_brut' => '',
                '110_totalGeneral_brut' => '',

                  /* <--! AMORTISSEMENT !--> */
                 '012_fondsCommercial_amortissements' => '',
                 '016_autres_amortissements' => '',
                 '030_immobiliCorporelles_amortissements' => '',
                 '042_immobiliFinanciere_amortissements' => '',
                 '048_total1_amortissements' => '',
                 '052_stock_matierePremiere_amortissements' => '',
                 '062_stock_marchandise_amortissements' => '',
                 '066_avances_acompte_amortissements' => '',
                 '070_creances_clientsEtCompte_amortissements' => '',
                 '074_creances_autres_amortissements' => '',
                 '082_valeursMobilieres_amortissements' => '',
                 '086_disponibilites_amortissements' => '',
                 '094_chargesConstatees_amortissements' => '',
                 '098_total2_amortissements' => '',
                 '112_totalGeneral_amortissements' => '',

                  /* <--! NET!--> */
                 '012_fondsCommercial_net' => '',
                 '016_autres_net' => '',
                 '030_immobiliCorporelles_net' => '',
                 '042_immobiliFinanciere_net' => '',
                 '048_total1_net' => '',
                 '052_stock_matierePremiere_net' => '',
                 '062_stock_marchandise_net' => '',
                 '066_avances_acompte_net' => '',
                 '070_creances_clientsEtCompte_net' => '',
                 '074_creances_autres_net' => '',
                 '082_valeursMobilieres_net' => '',
                 '086_disponibilites_net' => '',
                 '094_chargesConstatees_net' => '',
                 '098_total2_net' => '',
                 '112_totalGeneral_net' => '',

                  /* <--! PASSIF !--> */
                  /* <--! CAPITAUX PROPRES!--> */
                 '120_capitalSocialOuIndividuel_capitauxP' => '',
                 '124_ecartsReeval_capitauxP' => '',
                 '126_reserveLegal_capitauxP' => '',
                 '130_reserveReglementee_capitauxP' => '',
                 '131_reserveOeuvreDArt_capitauxP' => '',
                 '132_autresReserves_capitauxP' => '',
                 '134_reportNouveau_capitauxP' => '',
                 '136_resultatExercice_capitauxP' => '',
                 '137_subventionInvestissement_capitauxP' => '',
                 '140_provisionsReglementees_capitauxP' => '',
                 '142_total1_capitauxP' => '',
                 '142_total2_capitauxP' => '',

                  /* <--! DETTES !--> */
                 '156_empruntsEtDettes_dettes' => '',
                 '164_avancesEtAcomptesRecus_dettes' => '',
                 '166_fournisseursEtComptes_dettes' => '',
                 '169_TVA_dettesFiscalesEtSociales_dettes' => '',
                 '172_dettesFiscalesEtSociales_dettes' => '',
                 '173_comptesCourantsAssocies_dettes' => '',
                 '175_autresDettes_dettes' => '',
                 '174_produitsConstates_dettes' => '',
                 '176_total3_dettes' => '',
                 '180_totalGeneral_dettes' => '',

                  /* <--! RENVOIS !--> */
                 '193_1_immobilisationFinancieresMoins1an_renvois' => '',
                 '197_2_creancePlus1an_renvois' => '',
                 '199_3_compteCourantAssociesDebiteurs_renvois' => '',
                 '195_4_dettesPlus1an_renvois' => '',
                 '182_5_coutRevientImmoAcquisePendantExercice_renvois' => '',
                 '184_5_prixVenteHorsTVAdesImmoCedeesPendantExercice_renvois' => '',
            ];

            //Définit les variables du PDF
            $pdf = new Pdf($pdfPath);

            //fillForm méthode qui permet de récupérer les champs form du PDF les analysers récupérer leurs id type etc et en paramètre peut permettre la modification de ces derniers.
            $pdf->fillForm($fieldValues)
                ->needAppearances()
                ->saveAs($this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form_filled.pdf');

            //On Récupère le pdf via son pdfPath et on défini son type et sa disposition puis le génère
            return new Response(file_get_contents($this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form_filled.pdf'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="2033-sd_5015form.pdf"',
            ]);
        }


        //Route qui permet de vérifier la totalité des champs présent dans un PDF.
        #[Route('/pdf/fields', name: 'app_pdf_fields')]
        public function getPdfFields(): JsonResponse
        {
            //Définit la route du PDF
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/pdf/2033-sd_5015form.pdf';

            //Vérifie que le PDF est bien trouvé sinon retourne une erreur
            if (!file_exists($pdfPath)) {
                return new JsonResponse(['error' => 'Le fichier PDF est introuvable !'], 404);
            }
            
            //Définit les variables du PDF
            $pdf = new Pdf($pdfPath);
            $fields = $pdf->getDataFields(); //Méthode getDataFields pour récupérer automatique les champs d'un pdf.

            //Vérifie que les champs du pdf sont bien récupérer sinon retourne une erreur
            if(!$fields) {
                return new JsonResponse("La Data du formulaire n'a pas été récupérées", 404);
            }
            
            //Définit la variable qui vas stockés les champs du PDF
            $fieldData = [];

            //Boucle pour parcourir tous les champs et les stockés dans la variable dédiée
            foreach ($fields as $field) {
                $fieldData[] = [
                    'FieldName' => $field['FieldName'] ?? null,
                    'FieldType' => $field['FieldType'] ?? null,
                    'FieldValue' => $field['FieldValue'] ?? null,
                ];
            }
    
            return new JsonResponse($fieldData);
        }
    }
