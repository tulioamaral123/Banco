import java.util.Scanner;
import java.util.ArrayList;
import java.util.List;
import java.lang.Thread;

public class Main
{
    	private static ArrayList<Banco> listaBanco = new ArrayList<>();
	private static Scanner scanner = new Scanner(System.in);
	
	
	
	public static void main(String[] args) throws InterruptedException {
	    listaBanco.add(new Banco("00011122233", 10000));
	    listaBanco.add(new Banco("03981119100", 10000));
	    
	    boolean sair = false;
	    
	    
		while(!sair){
		    exibirMenu();
		    int opcao = scanner.nextInt();
		    scanner.nextLine();
		    
		    switch(opcao){
		        case 1:
		            inserirUsuario();
		            Thread.sleep(2000);
		            break;
		        case 2:
		            listarUsuarios();
		            Thread.sleep(2000);
		            System.out.println("Para continuar aperte ente: ");
		            String esperar = scanner.nextLine();
		            break;
		        case 3:
		            transferencia();
		            Thread.sleep(2000);
		            break;
		        case 4:
		            excluirUsuario();
		            Thread.sleep(2000);
		            break;
		        case 5:
		            System.out.println("Saindo!!!");
		            sair = true;
		            break;
		        default:
		            System.out.println("Opcao invalida, escolha novamente!!");
		            
		    }
		}
		
		
	}
	
	public static void exibirMenu(){ // EXIBIR MENU
        System.out.println("******Banco*******\n");
        System.out.println("1. Inserir um usuario\n");
        System.out.println("2. Listar usuarios\n");
        System.out.println("3. Transferencia de valor\n");
        System.out.println("4. Excluir um usuario\n");
        System.out.println("5. Sair do programa\n");

    }
    
    public static void inserirUsuario(){ // INSERIR USUARIO
        System.out.println("Insira o nome do usuario: ");
        String cpf = scanner.nextLine();
        

        
        
        for(Banco u: listaBanco){
            if(u.getCpf().equals(cpf)){
                System.out.println("Cpf ja cadastrado!!");
                return;
            }
        }
        
        System.out.println("\nInsira o valor: ");
        double dinheiro = scanner.nextInt();
        
        listaBanco.add(new Banco(cpf, dinheiro));
        System.out.println("\nUsuario e valor adicionados com sucesso\n");
    }
    
    public static void listarUsuarios(){ // LISTAR USUARIOS
        System.out.println("\nLista dos usuarios\n");
        
        if(listaBanco.isEmpty()){
            System.out.println("\nLista de usuarios esta vazia\n");
            
        }else{
            
            for(int i = 0; i <= listaBanco.size() - 1; i++){
                System.out.println((i + 1) + ". " + listaBanco.get(i));
            }
            
        }
        
    }
    
    private static void transferencia(){ // TRANSFERENCIA
        listarUsuarios();
        
        System.out.println("\nDigite o cpf do remetente: ");
        String cpfUm = scanner.nextLine();
        
        
        
        System.out.println("\nDigite o cpf do destinatario: \n");
        String cpfDois = scanner.nextLine();
        
        System.out.println("\nDigite o valor de transferencia: ");
        double valor = scanner.nextDouble();
        Banco remetente = null, destinatario = null;
        
        for(Banco a: listaBanco){
            if(a.getCpf().equals(cpfUm)){
                remetente = a;
            }
            if(a.getCpf().equals(cpfDois)){
                destinatario = a;
            }
        }
        
        if(remetente == null || destinatario == null){
            System.out.println("CPF nao encontrado");
        }else{
            if(remetente.Sacar(valor)){
                destinatario.Depositar(valor);
                System.out.println("Transferencia realizada com sucesso!");
            }else{
                System.out.println("Saldo insuficiente!!");
            }
            
        }
            
    }
    
    public static void excluirUsuario(){
        listarUsuarios();
        
        System.out.println("Digite o numero do usuario: ");
        int excluir = scanner.nextInt();
        scanner.nextLine();
        if (excluir > 0 && excluir <= listaBanco.size()) {
            listaBanco.remove(excluir - 1); // Remove pelo índice correto
            System.out.println("Usuário removido com sucesso!");
        } else {
            System.out.println("Número inválido! Tente novamente.");
        }
            
    }
    
}
