<?php
declare(strict_types=1);

namespace InvoiceNinjaModuleTest\Service;

use InvoiceNinjaModule\Exception\NotFoundException;
use InvoiceNinjaModule\Model\Interfaces\BaseInterface;
use InvoiceNinjaModule\Model\Interfaces\InvoiceInterface;
use InvoiceNinjaModule\Service\Interfaces\InvoiceManagerInterface;
use InvoiceNinjaModule\Service\Interfaces\ObjectServiceInterface;
use InvoiceNinjaModule\Service\InvoiceManager;
use PHPUnit\Framework\TestCase;

class InvoiceManagerTest extends TestCase
{
    /** @var  InvoiceManagerInterface */
    private $invoiceManager;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $objectManagerMock;

    protected function setUp() : void
    {
        parent::setUp();

        $this->objectManagerMock = $this->createMock(ObjectServiceInterface::class);
        $this->invoiceManager    = new InvoiceManager($this->objectManagerMock);
    }

    public function testCreate() : void
    {
        self::assertInstanceOf(InvoiceManagerInterface::class, $this->invoiceManager);
    }

    public function testCreateInvoice() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('createObject')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->createInvoice($invoiceMock));
    }

    public function testDelete() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('deleteObject')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->delete($invoiceMock));
    }


    public function testGetInvoiceById() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getObjectById')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::isType('integer'),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->getInvoiceById(777));
    }

    /**
     * @expectedException  \InvoiceNinjaModule\Exception\NotFoundException
     */
    public function testGetInvoiceByIdException() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('getObjectById')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::isType('integer'),
                self::stringContains('/invoices')
            )
            ->willThrowException(new NotFoundException());

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->getInvoiceById(777));
    }

    public function testGetInvoiceByNumber() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('findObjectBy')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::isType('array'),
                self::stringContains('/invoices')
            )
            ->willReturn([$invoiceMock]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        $result = $this->invoiceManager->getInvoiceByNumber('12345');
        self::assertInstanceOf(InvoiceInterface::class, $result);
        self::assertNotEmpty($result);
    }

    /**
     * @expectedException \InvoiceNinjaModule\Exception\NotFoundException
     */
    public function testGetInvoiceByNumberNotFound() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('findObjectBy')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::isType('array'),
                self::stringContains('/invoices')
            )
            ->willReturn([]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        $result = $this->invoiceManager->getInvoiceByNumber('12345');
        self::assertInstanceOf(InvoiceInterface::class, $result);
        self::assertNotEmpty($result);
    }

    /**
     * @expectedException \InvoiceNinjaModule\Exception\InvalidResultException
     */
    public function testGetInvoiceByNumberInvalid() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('findObjectBy')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::isType('array'),
                self::stringContains('/invoices')
            )
            ->willReturn([1,2,3]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        $result = $this->invoiceManager->getInvoiceByNumber('12345');
        self::assertInstanceOf(InvoiceInterface::class, $result);
        self::assertNotEmpty($result);
    }

    public function testUpdate() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('updateObject')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->update($invoiceMock));
    }

    public function testRestore() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('restoreObject')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->restore($invoiceMock));
    }

    public function testArchive() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('archiveObject')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices')
            )
            ->willReturn($invoiceMock);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInstanceOf(InvoiceInterface::class, $this->invoiceManager->archive($invoiceMock));
    }

    public function testGetAllInvoicesEmpty() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInternalType('array', $this->invoiceManager->getAllInvoices());
    }

    public function testGetAllInvoices() : void
    {
        $invoiceMock = $this->createMock(InvoiceInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([ 'test' => $invoiceMock ]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInternalType('array', $this->invoiceManager->getAllInvoices());
    }

    /**
     * @expectedException \InvoiceNinjaModule\Exception\InvalidResultException
     */
    public function testGetAllInvoicesOtherResult() : void
    {
        $invoiceMock = $this->createMock(BaseInterface::class);

        $this->objectManagerMock->expects(self::once())
            ->method('getAllObjects')
            ->with(
                self::isInstanceOf(InvoiceInterface::class),
                self::stringContains('/invoices'),
                self::isType('integer'),
                self::isType('integer')
            )
            ->willReturn([ 'test' => $invoiceMock ]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInternalType('array', $this->invoiceManager->getAllInvoices());
    }


    public function testDownloadInvoice() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('downloadFile')
            ->with(self::isType('integer'))
            ->willReturn([ 'test' => 'test' ]);

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        self::assertInternalType('array', $this->invoiceManager->downloadInvoice(10));
    }

    public function testSendEmailInvoice() : void
    {
        $this->objectManagerMock->expects(self::once())
            ->method('sendCommand')
            ->with(
                self::stringContains('email_invoice'),
                self::isType('array')
            );

        $this->invoiceManager = new InvoiceManager($this->objectManagerMock);

        $this->invoiceManager->sendEmailInvoice(10);
    }
}
