<?php

namespace App\Controller;

use App\Service\CallRequest;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends AbstractController
{
    public function __construct(
        private Pdf $pdf,
        private CallRequest $callRequest
    ) {}

    public function generatePdf(): Response
    {
        $user = $this->getUser();
        $soldArticle = $this->callRequest->GetSoldArticle();
        $receipts = $this->callRequest->GetReceipts($soldArticle);

        // Render the HTML template to a string
        $html = $this->renderView('pdf/index.html.twig', [
            'title' => 'Sample PDF',
            'content' => 'This is the content of the PDF.',
            'user' => $user,
            'sold_article' => $soldArticle,
            'receipts' => $receipts
        ]);

        // Generate PDF from the rendered HTML
        $pdfContent = $this->pdf->getOutputFromHtml($html);

        // Return the PDF as a response
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="receipts.pdf"',
            ]
        );
    }
}
